<?php

namespace App\Http\Controllers\Android\Aggregator;

use App\Classes\AndroidResponse;
use App\Classes\Notify;
use App\Classes\PushNotification;
use App\Http\Controllers\Controller;
use App\Mail\GeneralEmail;
use App\Mail\GeneralLinkEmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Classes\OrderIdString;

class OrdersController extends Controller
{
    //get_order_list_on_for_status

    public function get_order_list_on_for_status(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->user_id || !$request->status) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),
            ]);
        }
        try {
            $fetch_order_list_on_for_status = DB::table('orders')
                ->where('orders.assigned_delevery_partner_id', '=', $request->user_id)
                ->where('status_id', '=', $request->status)
                ->select('orders.id as order_id', 'orders.invoice_number', 'orders.total_payable_amount', 'orders.ordered_on', 'orders.last_status_timestamp','orders.status_id')
                ->get();
            foreach ($fetch_order_list_on_for_status as $order) {
                $order->address = DB::table('orders')
                    ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
                    ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
                    ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
                    ->select('pin_wise_village.village_name', 'order_address.house_no', 'order_address.area', 'order_address.landmark', 'order_address.city', 'order_address.state', 'pin_code.pincode', 'order_address.country')
                    ->where('orders.id', '=', $order->order_id)
                    ->first();
            }
            if (count($fetch_order_list_on_for_status) != 0) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Orders details for status fetched successfully.'),
                    'order_list_on_for_status' => $fetch_order_list_on_for_status,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! failled to fetch order details!'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrdersController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    //get_order_details

    public function get_order_details(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->order_id) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),
            ]);
        }
        try {
            $fetch_order_details = DB::table('orders')
                ->where('orders.id', '=', $request->order_id)
                ->select('orders.id as order_id', 'orders.invoice_number', 'orders.total_payable_amount', 'orders.ordered_on', 'orders.last_status_timestamp','orders.status_id')
                ->first();
            $fetch_order_details->address = DB::table('orders')
                ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
                ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
                ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
                ->select('pin_wise_village.village_name', 'order_address.house_no', 'order_address.area', 'order_address.landmark', 'order_address.city', 'order_address.state', 'pin_code.pincode', 'order_address.country')
                ->where('orders.id', '=', $request->order_id)
                ->first();
            $fetch_order_details->customer_details = DB::table('orders')
                ->leftJoin('customers', 'customers.id', '=', 'orders.ordered_by')
                ->select('customers.id as customer_id', 'customers.name as customer_name', 'customers.email as customer_email', 'customers.mobile_number as customer_mobile_number')
                ->where('orders.id', '=', $request->order_id)
                ->first();
            if ($fetch_order_details) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Orders detais fetched successfully.'),
                    'order_details' => $fetch_order_details,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! failled to fetch order details!'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrdersController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    //get_delivered_order

    public function get_delivered_order(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->user_id) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),
            ]);
        }
        try {
            $paginatedorders = DB::table('orders')
                ->where('orders.assigned_delevery_partner_id', '=', $request->user_id)
                ->where('orders.status_id', '=', '4')
                ->select('*')
                ->paginate($request->per_page ? $request->per_page : 10);
            if (count($paginatedorders->toArray()['data'])) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Delivered orders detais fetched successfully.'),
                    'delivered_orders_list' => $paginatedorders->toArray()['data'],
                    'total' => $paginatedorders->total(),
                    'per_page' => $paginatedorders->perPage(),
                    'current_page' => $paginatedorders->currentPage(),
                    'total_page' => $paginatedorders->lastPage(),
                    'from' => $paginatedorders->firstItem(),
                    'to' => $paginatedorders->lastItem(),
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No data found'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrdersController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }

    }

    //authenticate_delivery

    public function authenticate_delivery(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->user_id || !$request->order_id || !$request->otp) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),
            ]);
        }
        try {
            $check_authenticate_delivery = DB::table('orders')
                ->leftJoin('customers', 'customers.id', '=', 'orders.ordered_by')
                ->where('orders.id', '=', $request->order_id)
                ->select('orders.ordered_on','orders.assigned_delevery_partner_id', 'orders.otp', 'orders.status_id', 'orders.id as orders_id', 'customers.id as customer_id', 'customers.name as customer_name', 'customers.email as customer_email')
                ->first();

            if ($check_authenticate_delivery) {
                if ($check_authenticate_delivery->assigned_delevery_partner_id == $request->user_id) {

                    if ($check_authenticate_delivery->status_id == 3) {
                        if ($check_authenticate_delivery->otp == $request->otp) {
                            $update_status = DB::table('orders')
                                ->where('orders.id', '=', $request->order_id)
                                ->update(['orders.status_id' => '4', 'orders.last_status_timestamp' => time()]);
                            if ($update_status) {
                                DB::table('referal_details')
                                    ->where('referal_for', '=', $check_authenticate_delivery->customer_id)
                                    ->where('valid_till', '>', time())
                                    ->where('is_eligeble', '=', 0)
                                    ->where('is_used', '=', 0)
                                    ->orderBy('valid_till', 'ASC')
                                    ->limit(1)
                                    ->update(['referal_details.is_eligeble' => 1]);
                                $notify = new Notify();
                                $push_notification = new PushNotification();
                                $order_id_str = new OrderIdString();
                                $notify->notifyUser($check_authenticate_delivery->customer_id, 'Your order '.$order_id_str->get_str($check_authenticate_delivery->ordered_on,$check_authenticate_delivery->orders_id).' is delivered to you.', 'order', $check_authenticate_delivery->orders_id);
                                $user_payload = array([
                                    'action' => 'order',
                                    'action_keyword' => $check_authenticate_delivery->orders_id,
                                ]);
                                $push_notification->sendPush($check_authenticate_delivery->customer_id, '0', 'Your order '.$order_id_str->get_str($check_authenticate_delivery->ordered_on,$check_authenticate_delivery->orders_id).' is delivered to you.', 'order', $user_payload);
                                $USER_EMAIL = $check_authenticate_delivery->customer_email ?? 'nomail@grocerbee.co.in';
                                $USER_NAME = $check_authenticate_delivery->customer_name;
                                $MAIL_SUBJECT = 'You order is delivered to you. ';
                                $MAIL_BODY = 'Your order '.$order_id_str->get_str($check_authenticate_delivery->ordered_on,$check_authenticate_delivery->orders_id).' is delivered to you. Please rate us on Playstore if you like our service.';
                                $MAIL_LINK="https://play.google.com/store/apps/details?id=luci4.project.ecom_01";

                                Mail::send(
                                    new GeneralLinkEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY,$MAIL_LINK)
                                );

                                return response()->json([
                                    'status' => $androidResponse->getStatus(1, 'Delivery successful'),
                                ]);

                            } else {
                                return response()->json([
                                    'status' => $androidResponse->getStatus(0, 'cannot update order status!'),
                                ]);
                            }
                        } else {
                            return response()->json([
                                'status' => $androidResponse->getStatus(0, 'Sorry! OTP Invalid'),
                            ]);
                        }
                    } else if ($check_authenticate_delivery->status_id == 4) {
                        return response()->json([
                            'status' => $androidResponse->getStatus(0, 'Order already delivered'),
                        ]);
                    } else {
                        return response()->json([
                            'status' => $androidResponse->getStatus(0, 'Order is not in out for delivery state'),
                        ]);
                    }

                } else {
                    return response()->json([
                        'status' => $androidResponse->getStatus(0, 'Sorry! Your are not assigned for this order'),
                    ]);
                }
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! data not found'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrdersController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    //get_canceled_orders
    public function get_canceled_orders(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->user_id || !$request->start_time || !$request->end_time) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),
            ]);
        }
        try {
            $get_canceled_orders = DB::table('orders')
                ->select('*')
                ->where('orders.assigned_delevery_partner_id', '=', $request->user_id)
                ->where('orders.status_id', '=', '5')
                ->whereBetween('orders.ordered_on', [$request->start_time, $request->end_time])
                ->get();
            if (count($get_canceled_orders) != 0) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Canceled osrders fetched successfully.'),
                    'canceled_orders' => $get_canceled_orders,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No data found'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrdersController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    //out_for_delivery
    public function out_for_delivery(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->user_id || !$request->order_id) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),
            ]);
        }
        try {
            $check_ready_for_delivery = DB::table('orders')
                ->leftJoin('customers', 'customers.id', '=', 'orders.ordered_by')
                ->where('orders.assigned_delevery_partner_id', '=', $request->user_id)
                ->where('orders.id', '=', $request->order_id)
                ->select('orders.ordered_on','orders.status_id', 'orders.id as orders_id', 'customers.id as customer_id', 'customers.name as customer_name', 'customers.email as customer_email')
                ->first();
            if ($check_ready_for_delivery) {
                if ($check_ready_for_delivery->status_id == 2) {
                    $notify = new Notify();
                    $push_notification = new PushNotification();
                    $order_id_str = new OrderIdString();
                    $update_status = DB::table('orders')
                        ->where('orders.assigned_delevery_partner_id', '=', $request->user_id)
                        ->where('orders.id', '=', $request->order_id)
                        ->update(['orders.status_id' => '3', 'orders.last_status_timestamp' => time()]);
                    $notify->notifyUser($check_ready_for_delivery->customer_id, 'Your order '.$order_id_str->get_str($check_ready_for_delivery->ordered_on,$check_ready_for_delivery->orders_id).' is out for delivery. You can track your delivery anytime in the app.', 'order', $check_ready_for_delivery->orders_id);
                    $user_payload = array([
                        'action' => 'order',
                        'action_keyword' => $check_ready_for_delivery->orders_id,
                    ]);
                    $push_notification->sendPush($check_ready_for_delivery->customer_id, '0', 'Your order '.$order_id_str->get_str($check_ready_for_delivery->ordered_on,$check_ready_for_delivery->orders_id).' is ready to deliver. You can track your delivery anytime in the app.', 'order', $user_payload);
                    $USER_EMAIL = $check_ready_for_delivery->customer_email;
                    $USER_NAME = $check_ready_for_delivery->customer_name;
                    $MAIL_SUBJECT = 'You order is out for delivery';
                    $MAIL_BODY = 'Your order '.$order_id_str->get_str($check_ready_for_delivery->ordered_on,$check_ready_for_delivery->orders_id).' is out for delivery. You can track your delivery anytime in the app.';
                    Mail::send(
                        new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
                    );

                    return response()->json([
                        'status' => $androidResponse->getStatus(1, 'Status updated successfully!'),
                    ]);
                } else if ($check_ready_for_delivery->status_id == 3) {
                    return response()->json([
                        'status' => $androidResponse->getStatus(0, 'Order is already out for delivery'),
                    ]);
                } else {
                    return response()->json([
                        'status' => $androidResponse->getStatus(0, 'Order is not ready for delivery yet'),
                    ]);
                }

            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Order not found'),
                ]);
            }

        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrdersController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    public function fetch_notifications(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->user_id) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),
            ]);
        }
        try {
            $paginatedorders = DB::table('delivery_partner_notification')
                ->where('delivery_partner_notification.aggregator_id', '=', $request->user_id)
                ->select('*')
                ->orderBy('delivery_partner_notification.timestamp', 'desc')
                ->paginate($request->per_page ? $request->per_page : 10);
            if (count($paginatedorders->toArray()['data'])) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Notification detais fetched successfully.'),
                    'notification_list' => $paginatedorders->toArray()['data'],
                    'total' => $paginatedorders->total(),
                    'per_page' => $paginatedorders->perPage(),
                    'current_page' => $paginatedorders->currentPage(),
                    'total_page' => $paginatedorders->lastPage(),
                    'from' => $paginatedorders->firstItem(),
                    'to' => $paginatedorders->lastItem(),
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No data found'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrdersController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

}
