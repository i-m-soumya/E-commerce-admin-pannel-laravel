<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\GeneralEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Classes\PushNotification;
use App\Classes\Notify;
use App\Classes\OrderIdString;
use App\Classes\Extras;

class Order extends Controller
{
    public function fetch_order_details(Request $request)
    {
        $columns = array(
            0 => 'orders.id',
            // 1 => 'invoice_number',
            1 => 'customer_name',
            2 => 'orders.ordered_on',
            3 => 'pin_code',
            4 => 'village_name',
            5 => 'total_discount',
            6 => 'orders.total_payable_amount',
            7 => 'orders.status_id',
            8 => 'delivery_partner.name',
            9 => 'action',
        );
        $totalData = DB::table('orders')->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $ordering = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $orders = DB::table('orders')
            ->leftJoin('order_status_details', 'order_status_details.id', '=', 'orders.status_id')
            ->leftJoin('customers', 'customers.id', '=', 'orders.ordered_by')
            ->leftJoin('delivery_partner', 'delivery_partner.id', '=', 'orders.assigned_delevery_partner_id')
            ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
            ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
            ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
            ->select("orders.id as orders_id", "orders.ordered_on", "orders.total_discount", "orders.total_payable_amount", "orders.assigned_delevery_partner_id", "orders.status_id", "orders.invoice_number",
                "customers.name as customer_name",
                "delivery_partner.name as delivery_partner_name",
                "order_status_details.name  as order_status_name",
                'pin_wise_village.village_name', 'pin_code.pincode',

            );

        if (!empty($request->orders_status)) {
            $orders = $orders
                ->where('order_status_details.name', '=', $request->orders_status);

        }
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start_date = strtotime($request->start_date . " 00:00:00");
            $end_date = strtotime($request->end_date . " 23:59:59");
            $orders = $orders
                ->whereBetween('orders.last_status_timestamp', [$start_date, $end_date]);

        }
        if (!empty($request->date_filter)) {
            $date_filter_start = strtotime($request->date_filter . " 00:00:00");
            $date_filter_end = strtotime($request->date_filter . " 23:59:59");
            $orders = $orders
                ->whereBetween('orders.last_status_timestamp', [$date_filter_start, $date_filter_end]);
        }
        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');
            $orders = $orders
                ->whereRaw(" (orders.id LIKE '%{$search}%'
                OR orders.invoice_number LIKE '%{$search}%'
                OR customers.name LIKE '%{$search}%'
                OR pin_wise_village.village_name LIKE '%{$search}%'
                OR pin_code.pincode LIKE '%{$search}%'
                OR delivery_partner.name LIKE '%{$search}%'
                )");

        }
        $totalFiltered = $orders->count();
        $orders = $orders
            ->offset($start)
            ->limit($limit)
            ->orderBy($ordering, $dir)
            ->get();

        $data = array();
        if (!empty($orders)) {
            $sl_no = 1;
            foreach ($orders as $order) {
                // assign_delivery_partner
                if ($order->assigned_delevery_partner_id == "" && $order->status_id == 1) {
                    $delivery_assign = '<button class="btn btn-outline-primary btn-sm rounded-pill" data-toggle="modal" data-tooltip="Assign delivery partner" data-variation="mini" data-position="top right" data-target="#assign_delivery_partner_modal' . $order->orders_id . '" style="width:80px;" id="btn_assign" onclick="assign_delivery_partner(' . $order->orders_id . ')">Assign</button>';
                } else if ($order->assigned_delevery_partner_id != "" && $order->status_id == 2) {
                    $delivery_assign = $order->delivery_partner_name . '<button class="mt-1 mb-1  ui icon yellow tertiary button" data-tooltip="Modify" data-variation="mini" data-position="top right" data-toggle="modal" data-target="#assign_delivery_partner_modal' . $order->orders_id . '"   id="btn_assign" onclick="assign_delivery_partner(' . $order->orders_id . ')">
                        <i class="pen icon"></i>
                    </button>';
                } else {
                    $delivery_assign = $order->delivery_partner_name;
                }
                // action btn_string
                if ($order->status_id == 1) {
                    $action_btn = '
                        <button class="mt-1 mb-1  ui icon button mini inverted primary rounded-circle" data-tooltip="View Order" data-variation="mini" data-position="top right" data-toggle="modal" data-target="#view_order_modal' . $order->orders_id . '" onclick="view_order(' . $order->orders_id . ')">
                            <i class="eye icon"></i>
                        </button>
                        <button class="mt-1 mb-1  ui icon button mini inverted brown rounded-circle" id="export_order" data-tooltip="Export Order" data-variation="mini" data-position="top right" onclick="export_order_details(' . $order->orders_id . ')">
                            <i class="file export icon"></i>
                        </button>
                        <button class="mt-1 mb-1 ui icon button mini inverted red rounded-circle" id="reject_order_icon' . $order->orders_id . '" data-tooltip="Reject Order" data-variation="mini" data-position="top right" data-toggle="modal" data-target="#reject_order_modal' . $order->orders_id . '" onclick="reject_order(' . $order->orders_id . ')">
                            <i class="times icon"></i>
                        </button>';
                } else if ($order->status_id == 2) {
                    $action_btn = '
                        <button class="mt-1 mb-1  ui icon button mini inverted primary rounded-circle" data-tooltip="View Order" data-variation="mini" data-position="top right" data-toggle="modal" data-target="#view_order_modal' . $order->orders_id . '" onclick="view_order(' . $order->orders_id . ')">
                            <i class="eye icon"></i>
                        </button>
                        <button class="mt-1 mb-1  ui icon button mini inverted brown rounded-circle" id="export_order" data-tooltip="Export Order" data-variation="mini" data-position="top right" onclick="export_order_details(' . $order->orders_id . ')">
                            <i class="file export icon"></i>
                        </button>
                        <button class="mt-1 mb-1 ui icon button mini inverted red rounded-circle" id="reject_order_icon' . $order->orders_id . '" data-tooltip="Reject Order" data-variation="mini" data-position="top right" data-toggle="modal" data-target="#reject_order_modal' . $order->orders_id . '" onclick="reject_order(' . $order->orders_id . ')">
                        <i class="times icon"></i>

                        </button>';
                } else {
                    $action_btn = '
                    <button class="mt-1 mb-1  ui icon button mini inverted primary rounded-circle" data-tooltip="View Order" data-variation="mini" data-position="top right" data-toggle="modal" data-target="#view_order_modal' . $order->orders_id . '" onclick="view_order((' . $order->orders_id . '))">
                        <i class="eye icon"></i>
                    </button>
                    <button class="mt-1 mb-1  ui icon button mini inverted brown rounded-circle" data-tooltip="Export Order" data-variation="mini" data-position="top right" onclick="export_order_details(' . $order->orders_id . ')">
                        <i class="file export icon"></i>
                    </button>';
                }
                //create key for column value
                date_default_timezone_set("Asia/Calcutta");
                $timestamp = $order->ordered_on;
                $final_date_time = date("d-m-Y h:i  A", $timestamp);
                $order_id_str = new OrderIdString();
                $nestedData['sl_no'] = $sl_no;
                $nestedData['order_id'] = '<a class="text-link" data-toggle="modal" data-target="#view_order_modal' . $order->orders_id . '" onclick="view_order(' . $order->orders_id . ')"><strong>'.$order_id_str->get_str($order->ordered_on,$order->orders_id).'</strong></a>';
                // $nestedData['invoice_number'] = $order->invoice_number;
                $nestedData['customer_name'] = $order->customer_name;
                $nestedData['date_time'] = $final_date_time;
                $nestedData['pin_code'] = $order->pincode;
                $nestedData['village_name'] = $order->village_name;
                $nestedData['total_discount'] = "$order->total_discount";
                $nestedData['payable_amount'] = $order->total_payable_amount;
                $nestedData['status'] = $order->order_status_name;
                $nestedData['delivery_boy'] = $delivery_assign;
                $nestedData['action'] = $action_btn;

                $data[] = $nestedData;
                $sl_no++;
            }
            //create_json
            $json_data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data,
            );
            //return_json
            return json_encode($json_data);
        }
    }
    //fetch_delivery_partner_details
    public function fetch_delivery_partner_details()
    {
        $fetch_delivery_partner_details = DB::table('delivery_partner')
            ->select("*")
            ->where("is_deleted", "=", "0")
            ->get();
        return json_encode($fetch_delivery_partner_details);
    }
    //assign_delivery_partner
    public function assign_delivery_partner(Request $req)
    {
        $response = array();
        $notify = new Notify();
        $push_notification = new PushNotification();
        $order_id_str = new OrderIdString();
        $assign_delivery_partner_id = DB::table('orders')
            ->where('id', $req->orders_id)
            ->select('assigned_delevery_partner_id')->first();
        if ($assign_delivery_partner_id->assigned_delevery_partner_id == "") {
            $update_assign_delivery_partner_id = DB::table('orders')
                ->where('id', $req->orders_id)
                ->update(['assigned_delevery_partner_id' => $req->delivery_partner_id, 'status_id' => '2', 'last_status_timestamp' => time()]);

            $need_email_details = DB::table('orders')
                ->leftJoin('delivery_partner', 'delivery_partner.id', '=', 'orders.assigned_delevery_partner_id')
                ->leftJoin('customers', 'customers.id', '=', 'orders.ordered_by')
                ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
                ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
                ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
                ->select('orders.ordered_on','orders.id as orders_id','delivery_partner.name as delivery_partner_name', 'delivery_partner.email',
                    'order_address.city', 'order_address.house_no', 'order_address.area', 'order_address.landmark', 'order_address.state', 'order_address.country',
                    'pin_wise_village.village_name', 'pin_code.pincode','customers.id as customer_id')
                ->where('orders.id', '=', $req->orders_id)
                ->first();

                $notify->notifyAggregator($req->delivery_partner_id,'Grocerbee Partner,You are assigned to deliver the order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' to address '.$need_email_details->village_name.','.$need_email_details->area.','.$need_email_details->city.','.$need_email_details->house_no.',Near:'.$need_email_details->landmark.','.$need_email_details->state.'-'.$need_email_details->pincode.'. Please pick up the packed from our hub as soon as possible.','order',$need_email_details->orders_id);
                $notify->notifyUser($need_email_details->customer_id,'Your order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' is ready to deliver. You can track your delivery anytime in the app.','order',$need_email_details->orders_id);

                $aggregator_payload=array([
                    'action'=>'order',
                    'action_keyword'=>$need_email_details->orders_id,
                ]);
                $user_payload=array([
                    'action'=>'order',
                    'action_keyword'=>$need_email_details->orders_id,
                ]);

                $push_notification->sendPush($req->delivery_partner_id, '1', 'Grocerbee Partner,You are assigned to deliver the order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' to address '.$need_email_details->village_name.','.$need_email_details->area.','.$need_email_details->city.','.$need_email_details->house_no.',Near:'.$need_email_details->landmark.','.$need_email_details->state.'-'.$need_email_details->pincode.'. Please pick up the packed from our hub as soon as possible.', 'Grocerbee Services', $aggregator_payload);
                $push_notification->sendPush($need_email_details->customer_id, '0', 'Your order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' is ready to deliver. You can track your delivery anytime in the app.','order', $user_payload);

                //email
            $USER_EMAIL = $need_email_details->email;
            $USER_NAME = $need_email_details->delivery_partner_name;
            $MAIL_SUBJECT = 'You are assigned for a order';
            $MAIL_BODY = 'Grocerbee Partner,You are assigned to deliver the order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' to address '.$need_email_details->village_name.','.$need_email_details->area.','.$need_email_details->city.','.$need_email_details->house_no.',Near:'.$need_email_details->landmark.','.$need_email_details->state.'-'.$need_email_details->pincode.'. Please pick up the packed from our hub as soon as possible.';

            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
            $response['success'] = 1;
            $response['message'] = "Delivery partner asign successfully";
            // $response['delivery_partner_id'] =$need_email_details;
            echo json_encode($response);

        } else if ($assign_delivery_partner_id->assigned_delevery_partner_id != "") {

            $notify = new Notify();
            $push_notification = new PushNotification();
            $order_id_str = new OrderIdString();
            $need_email_details = DB::table('orders')
                ->leftJoin('delivery_partner', 'delivery_partner.id', '=', 'orders.assigned_delevery_partner_id')
                ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
                ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
                ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
                ->select('orders.ordered_on','orders.id as orders_id','delivery_partner.name as previous_delivery_partner_name', 'delivery_partner.email as previous_delivery_partner_email',
                    'order_address.city', 'order_address.house_no', 'order_address.area', 'order_address.landmark', 'order_address.state', 'order_address.country',
                    'pin_wise_village.village_name', 'pin_code.pincode')
                ->where('orders.id', '=', $req->orders_id)
                ->first();
                $aggregator_unassigned_payload=array([
                    'action'=>'order',
                    'action_keyword'=>$need_email_details->orders_id,
                ]);
                $notify->notifyAggregator($req->delivery_partner_id,'Grocerbee Partner,You are unassigned from the delivery of order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' to address '.$need_email_details->village_name.','.$need_email_details->area.','.$need_email_details->city.','.$need_email_details->house_no.',Near:'.$need_email_details->landmark.','.$need_email_details->state.'-'.$need_email_details->pincode.'.','order',$need_email_details->orders_id);
                $push_notification->sendPush($req->delivery_partner_id, '1', 'Grocerbee Partner,You are unassigned from the delivery of order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' to address '.$need_email_details->village_name.','.$need_email_details->area.','.$need_email_details->city.','.$need_email_details->house_no.',Near:'.$need_email_details->landmark.','.$need_email_details->state.'-'.$need_email_details->pincode.'.','order', $aggregator_unassigned_payload);
            //unassigned_email
            $USER_EMAIL = $need_email_details->previous_delivery_partner_email;
            $USER_NAME = $need_email_details->previous_delivery_partner_name;
            $MAIL_SUBJECT = 'You are unassigned for a order';
            $MAIL_BODY = 'Grocerbee Partner,You are unassigned from the delivery of order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' to address '.$need_email_details->village_name.','.$need_email_details->area.','.$need_email_details->city.','.$need_email_details->house_no.',Near:'.$need_email_details->landmark.','.$need_email_details->state.'-'.$need_email_details->pincode.'.';

            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
            $update_assign_delivery_partner_id = DB::table('orders')
                ->where('id', $req->orders_id)
                ->update(['assigned_delevery_partner_id' => $req->delivery_partner_id, 'status_id' => '2']);
            $new_delivery_partner = DB::table('orders')
                ->leftJoin('delivery_partner', 'delivery_partner.id', '=', 'orders.assigned_delevery_partner_id')
                ->select('delivery_partner.name as new_delivery_partner_name', 'delivery_partner.email')
                ->where('orders.id', '=', $req->orders_id)
                ->first();

                $aggregator_assigned_payload=array([
                    'action'=>'order',
                    'action_keyword'=>$need_email_details->orders_id,
                ]);
                $notify->notifyAggregator($req->delivery_partner_id,'Grocerbee Partner,You are assigned to deliver the order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' to address '.$need_email_details->village_name.','.$need_email_details->area.','.$need_email_details->city.','.$need_email_details->house_no.',Near:'.$need_email_details->landmark.','.$need_email_details->state.'-'.$need_email_details->pincode.'. Please pick up the packed from our hub as soon as possible.','order',$need_email_details->orders_id);
                $push_notification->sendPush($req->delivery_partner_id, '1', 'Grocerbee Partner,You are assigned to deliver the order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' to address '.$need_email_details->village_name.','.$need_email_details->area.','.$need_email_details->city.','.$need_email_details->house_no.',Near:'.$need_email_details->landmark.','.$need_email_details->state.'-'.$need_email_details->pincode.'. Please pick up the packed from our hub as soon as possible.', 'Grocerbee Services', $aggregator_payload);
            //assigned_email
            $USER_EMAIL = $new_delivery_partner->email;
            $USER_NAME = $new_delivery_partner->new_delivery_partner_name;
            $MAIL_SUBJECT = 'You are assigned for a order';
            $MAIL_BODY = 'Grocerbee Partner,You are assigned to deliver the order '.$order_id_str->get_str($need_email_details->ordered_on,$need_email_details->orders_id).' to address '.$need_email_details->village_name.','.$need_email_details->area.','.$need_email_details->city.','.$need_email_details->house_no.',Near:'.$need_email_details->landmark.','.$need_email_details->state.'-'.$need_email_details->pincode.'. Please pick up the packed from our hub as soon as possible.';

            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
            $response['success'] = 1;
            $response['message'] = "Delivery partner asign successfully";
            // $response['delivery_partner_id'] =$need_email_details;
            echo json_encode($response);

        } else {

            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);

        }
    }
    //fetch_order_full_details(contain item details also)
    public function fetch_order_full_details(Request $req)
    {
        $fetched_order_details = DB::table('orders')
            ->leftJoin('delivery_partner', 'delivery_partner.id', '=', 'orders.assigned_delevery_partner_id')
            ->leftJoin('customers', 'customers.id', '=', 'orders.ordered_by')
            ->leftJoin('order_status_details', 'order_status_details.id', '=', 'orders.status_id')
            ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
            ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
            ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
            ->select('orders.delivery_charge','delivery_partner.name as delivery_patner_name',
                'customers.name as customer_name', 'customers.email as customer_email', 'customers.mobile_number as customer_mobile_number',
                'order_status_details.id as order_status_id', 'order_status_details.name as order_status_name',
                'order_address.city', 'order_address.house_no', 'order_address.area', 'order_address.landmark', 'order_address.state', 'order_address.country',
                'pin_wise_village.village_name', 'pin_code.pincode',
                'orders.total_discount', 'orders.total_payable_amount', 'orders.applied_coupon_amount'
            )
            ->where('orders.id', '=', $req->orders_id)
            ->get();
        foreach ($fetched_order_details as $item) {
            $item->item_details = DB::table('orders_details')
                ->leftjoin('orders', 'orders.id', '=', 'orders_details.order_id')
                ->leftjoin('product', 'product.id', '=', 'orders_details.product_id')
                ->select('orders_details.quantity as item_quantity', 'orders_details.per_qty_mrp', 'orders_details.per_qty_sell_price', 'orders_details.per_qty_discount',
                    'product.name as item_name')
                ->where('orders_details.order_id', '=', $req->orders_id)
                ->get();
        }
        echo json_encode($fetched_order_details);
    }
    public function export_order_details(Request $req)
    {
        $all_data = DB::table('orders')
            ->leftJoin('customers', 'customers.id', '=', 'orders.ordered_by')
            ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
            ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
            ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
            ->select('orders.delivery_charge','orders.id as orders_id', 'orders.ordered_on',
                'customers.name as customer_name', 'customers.email as customer_email', 'customers.mobile_number as customer_mobile_number',
                'order_address.city', 'order_address.house_no', 'order_address.area', 'order_address.landmark', 'order_address.state', 'order_address.country',
                'pin_wise_village.village_name', 'pin_code.pincode',
                'orders.total_discount', 'orders.total_payable_amount', 'orders.applied_coupon_amount', 'orders.invoice_number'
            )
            ->where('orders.id', '=', $req->orders_id)
            ->get();
        foreach ($all_data as $data) {
            $data->item_details = DB::table('orders_details')
                ->leftjoin('orders', 'orders.id', '=', 'orders_details.order_id')
                ->leftjoin('product', 'product.id', '=', 'orders_details.product_id')
                ->select('orders_details.quantity as item_quantity', 'orders_details.per_qty_mrp', 'orders_details.per_qty_sell_price', 'orders_details.per_qty_discount',
                    'product.name as item_name')
                ->where('orders_details.order_id', '=', $req->orders_id)
                ->get();
        }
        $get_logo_link = new Extras();
        $logo_link=$get_logo_link->get_logo_link();
        $pdf = PDF::loadView('pdf.order_invoice_pdf', ['all_data' => $all_data , 'logo_link' => $logo_link]);
        return $pdf->stream('pdf_file.pdf');
    }
    public function reject_order(Request $req)
    {
        $response = array();
         $notify = new Notify();
            $push_notification = new PushNotification();
            $order_id_str = new OrderIdString();
        $update_status_and_reason = DB::table('orders')
            ->where('id', $req->orders_id)
            ->update(['status_id' => '6', 'cancellation_reason_id' => $req->rejected_reason, 'last_status_timestamp' => time()]);
        $customer_details = DB::table('orders')
            ->leftJoin('customers', 'customers.id', '=', 'orders.ordered_by')
            ->leftjoin('cancellation_reason', 'cancellation_reason.id', '=', 'cancellation_reason_id')
            ->select('orders.ordered_on','orders.id as orders_id','orders.assigned_delevery_partner_id','customers.id as customer_id','customers.name as customer_name', 'customers.email as customer_email', 'customers.mobile_number as customer_mobile_number', 'cancellation_reason.reason as cancellation_reason','orders.status_id')
            ->where('orders.id', '=', $req->orders_id)
            ->first();
            $customer_payload=array([
                'action'=>'Order rejected',
                'action_keyword'=>$customer_details->customer_id,
            ]);
            $notify->notifyUser($customer_details->customer_id,'Your order '.$order_id_str->get_str($customer_details->ordered_on,$customer_details->orders_id).' is declined due to'. $customer_details->cancellation_reason . '.Sorry for the inconvinience.','Order',$customer_details->customer_id);
            $push_notification->sendPush($customer_details->customer_id, '0', 'Your order '.$order_id_str->get_str($customer_details->ordered_on,$customer_details->orders_id).' is declined due to'. $customer_details->cancellation_reason . '.Sorry for the inconvinience.', 'Order', $customer_payload);
        //send_email
        $USER_EMAIL = $customer_details->customer_email;
        $USER_NAME = $customer_details->customer_name;
        $MAIL_SUBJECT = 'Your order '.$order_id_str->get_str($customer_details->ordered_on,$customer_details->orders_id).' is declined';
        $MAIL_BODY = 'Your order '.$order_id_str->get_str($customer_details->ordered_on,$customer_details->orders_id).' is declined due to '. $customer_details->cancellation_reason . '.';

        Mail::send(
            new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
        );
        $assign_delivery_partner_id = DB::table('orders')
            ->where('id', $req->orders_id)
            ->select('assigned_delevery_partner_id')->first();
        if ($assign_delivery_partner_id->assigned_delevery_partner_id != "") {
            $delivery_partner_details = DB::table('orders')
                ->leftJoin('delivery_partner', 'delivery_partner.id', '=', 'orders.assigned_delevery_partner_id')
                ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
                ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
                ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
                ->select('orders.id as orders_id','delivery_partner.name as delivery_partner_name', 'delivery_partner.email as delivery_partner_email',
                    'order_address.city', 'order_address.house_no', 'order_address.area', 'order_address.landmark', 'order_address.state', 'order_address.country',
                    'pin_wise_village.village_name', 'pin_code.pincode',
                    'orders.id as orders_id', 'orders.ordered_on','orders.assigned_delevery_partner_id')
                ->where('orders.id', '=', $req->orders_id)
                ->first();
            $notify->notifyAggregator($delivery_partner_details->assigned_delevery_partner_id,'Order  '.$order_id_str->get_str($delivery_partner_details->ordered_on,$delivery_partner_details->orders_id).' is rejected by admin ','Order',$delivery_partner_details->orders_id);
            $aggregator_payload=array([
                'action'=>'Order',
                'action_keyword'=>$delivery_partner_details->orders_id,
            ]);
            $push_notification->sendPush($customer_details->customer_id, '1', 'Order '.$order_id_str->get_str($delivery_partner_details->ordered_on,$delivery_partner_details->orders_id).' rejected by admin', 'Order', $aggregator_payload);
            //rejected aggregrator email
            $USER_EMAIL = $delivery_partner_details->delivery_partner_email;
            $USER_NAME = $delivery_partner_details->delivery_partner_name;
            $MAIL_SUBJECT = 'Order rejected by admin';
            $MAIL_BODY = 'Order '.$order_id_str->get_str($delivery_partner_details->ordered_on,$delivery_partner_details->orders_id).' is rejected by admin ';
            //ORDER ID #1 has been calceled due to (xyz reason) on xyz address.
            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
        }
        if ($customer_details) {
            $response['success'] = 1;
            $response['message'] = "Order rejected successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }
}
