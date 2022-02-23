<?php

namespace App\Http\Controllers\Android;

use App\Classes\AndroidResponse;
use App\Classes\PushNotification;
use App\Http\Controllers\Controller;
use App\Mail\GeneralEmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();

            $order_details = json_decode($request->order);

            $village_is_available = DB::table('pin_wise_village')

                ->where('pin_wise_village.id', $order_details->address->village->id)

                ->where('pin_wise_village.is_active', 1)

                ->where('pin_code.is_active', 1)

                ->join('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')

                ->select('pin_wise_village.id')

                ->first();

            if (!$village_is_available) {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! We are unable to deliver in this Village or Pincode.'),

                ]);
            }

            if (!$order_details->orderProducts) {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! Please add some products in your cart.'),

                ]);
            }

            if (!$order_details->userId) {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! User id is missing.'),

                ]);
            }

            $calculated_price = 0;
            foreach ($order_details->orderProducts as $product) {
                $calculated_price = $calculated_price + ($product->sellingPrice * $product->quantity);
            }
            $calculated_price = $calculated_price - $order_details->additionalDiscount;
            $calculated_price = $calculated_price + $order_details->deliveryCharge;
            $calculated_price = round($calculated_price);
            $calculated_price = number_format($calculated_price, 2, '.', '');
            if ($calculated_price != $order_details->netPayable) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! Net Payable is not matching.'),
                ]);
            }

            $is_customer_exist = DB::table('customers')

                ->where('id', $order_details->userId)

                ->select('id', 'name', 'email', 'mobile_number', 'profile_image_upload_id', 'created_at', 'last_active')

                ->first();

            if (!$is_customer_exist) {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! We are unable to find your details in our database.'),

                ]);
            }

            if ($order_details->address) {
                $inserted_address_id = DB::table('order_address')

                    ->insertGetId(
                        [

                            'customer_id' => $order_details->userId ?? null,

                            'city' => $order_details->address->city ?? null,

                            'village_id' => $order_details->address->village->id ?? null,

                            'house_no' => $order_details->address->houseNo ?? null,

                            'area' => $order_details->address->area ?? null,

                            'landmark' => $order_details->address->landmark ?? null,

                            'state' => $order_details->address->state ?? null,

                            'country' => $order_details->address->country ?? null,

                        ]
                    );

                $new_order_id = DB::table('orders')

                    ->insertGetId(
                        [

                            'assigned_delevery_partner_id' => null,

                            'ordered_by' => $order_details->userId ?? null,

                            'status_id' => 1,

                            'total_mrp' => $order_details->totalPrice ?? null,

                            'total_cgst' => null,

                            'total_sgst' => null,

                            'total_igst' => null,

                            'total_discount' => $order_details->discount ?? null,

                            'total_sell_price' => $order_details->netAmount ?? null,
                            'applied_coupon_amount' => $order_details->additionalDiscount ?? null,
                            'total_payable_amount' => $order_details->netPayable ?? null,
                            'order_address_id' => $inserted_address_id ?? null,
                            'ordered_on' => time(),
                            'is_referral' => $order_details->usingReferralCoupon ? 1 : 0,
                            'invoice_number' => "#IN-" . hash('adler32', $order_details->userId . "-" . time() . "-" . $inserted_address_id),
                            'cancellation_reason_id' => null,
                            'delivery_charge' => $order_details->deliveryCharge ?? null,
                            'otp' => rand(10000, 99999),
                            'last_status_timestamp' => time(),
                        ]
                    );

                foreach ($order_details->orderProducts as $product) {
                    $new_order_details_id = DB::table('orders_details')

                        ->insertGetId(
                            [

                                'product_id' => $product->id ?? null,

                                'quantity' => $product->quantity ?? null,

                                'per_qty_mrp' => $product->mrp ?? null,

                                'per_qty_discount' => $product->discount ?? null,

                                'per_qty_sell_price' => $product->sellingPrice ?? null,

                                'order_id' => $new_order_id ?? null,

                                'name' => $product->name ?? null,

                                'description' => $product->description ?? null,

                                'per_qty_cgst' => null,

                                'per_qty_sgst' => null,

                                'per_qty_igst' => null,

                            ]
                        );
                }

                if ($request->clear_cart) {
                    $cart = DB::table('cart')

                        ->where('cart.is_active', 1)

                        ->where('cart.customer_id', $order_details->userId)

                        ->first();

                    if ($cart) {
                        $update_cart = DB::table('cart_products')

                            ->where('cart_products.is_active', 1)

                            ->where('cart_products.cart_id', $cart->id)

                            ->update(['cart_products.is_active' => 0]);
                    }
                }
                if ($order_details->usingReferralCoupon) {
                    DB::table('referal_details')
                        ->where('referal_to', '=', $order_details->userId)
                        ->where('valid_till', '>', time())
                        ->where('is_eligeble', '=', 1)
                        ->where('is_used', '=', 0)
                        ->orderBy('valid_till', 'ASC')
                        ->limit(1)
                        ->update(['referal_details.is_used' => 1, 'referal_details.used_on' => time()]);
                }

                return response()->json([

                    'status' => $androidResponse->getStatus(1, 'Order Placed!'),

                    'order_id' => $new_order_id,

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrderController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }
    public function orderList(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            if (!$request->user_id) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! User id is required.'),
                ]);
            }
            $order_list = DB::table('orders')
                ->where('ordered_by', $request->user_id)
                ->join('order_status_details', 'order_status_details.id', '=', 'orders.status_id')
                ->select('orders.id', 'order_status_details.name as status', 'order_status_details.id as status_id', 'orders.ordered_on', 'total_mrp', 'total_discount', 'total_sell_price', 'applied_coupon_amount', 'total_payable_amount', 'invoice_number')
                ->orderBy('orders.ordered_on', 'desc')
                ->get();
            if (!count($order_list)) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'You have not ordered anything yet!'),
                ]);
            }
            foreach ($order_list as $orders) {
                $count = DB::table('orders_details')
                    ->where('orders_details.order_id', '=', $orders->id)
                    ->selectRaw('count(orders_details.id) as count')
                    ->first();
                $orders->product_count = $count->count;
                $product_name = DB::table('orders_details')
                    ->where('orders_details.order_id', '=', $orders->id)
                    ->select('orders_details.name')
                    ->first();
                $orders->product_name = $product_name->name;
            }
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Orders found!'),
                'order_id' => $order_list,
            ]);
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrderController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    public function orderDetails(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            if (!$request->user_id) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! Please provide all required data.'),
                ]);
            }

            $order_details = DB::table('orders')
                ->where('ordered_by', $request->user_id)
                ->where('orders.id', $request->order_id)
                ->join('order_status_details', 'order_status_details.id', '=', 'orders.status_id')
                ->select('orders.id', 'order_status_details.name as status', 'order_status_details.id as status_id', 'orders.last_status_timestamp as last_status_timestamp', 'orders.otp as otp', 'orders.delivery_charge', 'orders.ordered_on', 'orders.order_address_id', 'total_mrp', 'total_discount', 'total_sell_price', 'applied_coupon_amount', 'total_payable_amount', 'invoice_number')
                ->first();
            if (!$order_details) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! We are unable to find records in our system.'),
                ]);
            }
            $count = DB::table('orders_details')
                ->where('orders_details.order_id', '=', $order_details->id)
                ->selectRaw('count(orders_details.id) AS count')
                ->first();
            $order_details->product_count = $count->count;
            $product_name = DB::table('orders_details')
                ->where('orders_details.order_id', '=', $order_details->id)
                ->select('orders_details.name')
                ->first();
            $order_details->product_name = $product_name->name;
            $products = DB::table('orders_details')
                ->where('orders_details.order_id', '=', $order_details->id)
                ->select('product_id', 'quantity', 'per_qty_mrp', 'per_qty_discount', 'per_qty_sell_price', 'name', 'description')
                ->get();
            $order_details->products = $products;
            $address = DB::table('order_address')
                ->where('order_address.id', '=', $order_details->order_address_id)
                ->join('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
                ->join('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
                ->select('order_address.city', 'order_address.house_no', 'order_address.area', 'order_address.landmark', 'pin_wise_village.village_name', 'pin_code.pincode as pin_code', 'order_address.state', 'order_address.country')
                ->first();
            $order_details->address = $address;
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Orders found!'),
                'order_id' => $order_details,
            ]);
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrderController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
    public function push(Request $request)
    {
        $PushNotification = new PushNotification();
        $PushNotification->sendPush(1, "DUMMy",null,null,null);
    }

    //cancel order

    public function cancel_order(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            if (!$request->user_id || !$request->order_id) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! User id and order_id is required.'),
                ]);
            }
            $check_order = DB::table('orders')
                ->where('ordered_by', $request->user_id)
                ->where('orders.id', $request->order_id)
                ->first();

            if ($check_order) {
                $update_order_status = DB::table('orders')
                    ->where('ordered_by', $request->user_id)
                    ->where('orders.id', $request->order_id)
                    ->update(['orders.status_id' => '5']);

                if ($update_order_status) {
                    if ($check_order->status_id == 2 || $check_order->status_id == 3) {
                        $fetch_delivery_partner = DB::table('orders')
                            ->leftJoin('delivery_partner', 'delivery_partner.id', '=', 'orders.assigned_delevery_partner_id')
                            ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
                            ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
                            ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
                            ->select(
                                'delivery_partner.name as delivery_partner_name',
                                'delivery_partner.email as delivery_partner_email',
                                'order_address.city',
                                'order_address.house_no',
                                'order_address.area',
                                'order_address.landmark',
                                'order_address.state',
                                'order_address.country',
                                'pin_wise_village.village_name',
                                'pin_code.pincode',
                                'orders.id as orders_id',
                                'orders.ordered_on'
                            )
                            ->where('orders.id', '=', $request->order_id)
                            ->first();
                        date_default_timezone_set("Asia/Calcutta");
                        $timestamp = $fetch_delivery_partner->ordered_on;
                        $final_date_time = date("mdY", $timestamp);
                        //rejected aggregrator email
                        $USER_EMAIL = $fetch_delivery_partner->delivery_partner_email;
                        $USER_NAME = $fetch_delivery_partner->delivery_partner_name;
                        $MAIL_SUBJECT = 'Order canceled';
                        $MAIL_BODY = 'Order Id: OD' . $final_date_time . $fetch_delivery_partner->orders_id . ' has been canceled by user  on ' . $fetch_delivery_partner->house_no . ', ' . $fetch_delivery_partner->village_name . ', ' . $fetch_delivery_partner->area . ', ' . $fetch_delivery_partner->landmark . ', ' . $fetch_delivery_partner->city . ', ' . $fetch_delivery_partner->state . ' - ' . $fetch_delivery_partner->pincode;
                        Mail::send(
                            new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
                        );
                        return response()->json([
                            'status' => $androidResponse->getStatus(1, 'Order canceled successfully!'),
                        ]);
                    } else {
                        return response()->json([
                            'status' => $androidResponse->getStatus(1, 'Order canceled successfully!'),
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => $androidResponse->getStatus(0, 'Unable to update order status! Please try again '),
                    ]);
                }
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Order not found'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrderController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
}
