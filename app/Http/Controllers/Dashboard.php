<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\GeneralEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Session;

class Dashboard extends Controller
{
    public function fetch_total_sales_and_orders()
    {
        $response = array();
        date_default_timezone_set("Asia/Calcutta");
        $start = date("Y-m-1 00:00:00");
        $start = strtotime($start);
        $end = date("Y-m-d H:i:s");
        $end = strtotime($end);
        $fetch_total_sales_and_orders = DB::table('orders')
            ->select(DB::raw('SUM(orders.total_payable_amount) as total_sales,count(orders.id) as total_orders'))
            ->where('orders.status_id', '=', 4)
            ->whereBetween('orders.last_status_timestamp', [$start, $end])
            ->get();

        $fdate = date("Y-m-d 00:00:00", strtotime("first day of previous month"));
        $ldate = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
        $fdate = strtotime($fdate);
        $ldate = strtotime($ldate);
        $year_fdate = strtotime(date("Y-01-01 00:00:00"));
        $year_ldate = strtotime(date("Y-12-31 23:59:59"));
        $previous_year_fdate = strtotime(date("Y-01-01 00:00:00", strtotime("-1 year")));
        $previous_year_ldate = strtotime(date("Y-12-31 23:59:59", strtotime("-1 year")));
        $fetch_previous_total_sales_and_orders = DB::table('orders')
            ->select(DB::raw('SUM(orders.total_payable_amount) as previous_total_sales,count(orders.id) as previous_total_orders'))
            ->where('orders.status_id', '=', 4)
            ->whereBetween('orders.last_status_timestamp', [$fdate, $ldate])
            ->get();
        $fetch_total_sales_and_orders_yearly = DB::table('orders')
            ->select(DB::raw('SUM(orders.total_payable_amount) as year_total_sales,count(orders.id) as year_total_orders'))
            ->where('orders.status_id', '=', 4)
            ->whereBetween('orders.last_status_timestamp', [$year_fdate, $year_ldate])
            ->get();
        $fetch_previous_total_sales_and_orders_yearly = DB::table('orders')
            ->select(DB::raw('SUM(orders.total_payable_amount) as previous_year_total_sales,count(orders.id) as previous_year_total_orders'))
            ->where('orders.status_id', '=', 4)
            ->whereBetween('orders.last_status_timestamp', [$previous_year_fdate, $previous_year_ldate])
            ->get();
        $response['total_sales_and_orders'] = $fetch_total_sales_and_orders;
        $response['previous_total_sales_and_orders'] = $fetch_previous_total_sales_and_orders;
        $response['fetch_total_sales_and_orders_yearly'] = $fetch_total_sales_and_orders_yearly;
        $response['fetch_previous_total_sales_and_orders_yearly'] = $fetch_previous_total_sales_and_orders_yearly;
        echo json_encode($response);
    }

    public function fetch_new_members()
    {
        $response = array();
        date_default_timezone_set("Asia/Calcutta");
        $start = date("Y-m-1 00:00:00");
        $start = strtotime($start);
        $end = date("Y-m-d H:i:s");
        $end = strtotime($end);
        $fetch_total_customers = DB::table('customers')
            ->select(DB::raw('count(customers.id) as total_customers'))
            ->whereBetween('customers.created_at', [$start, $end])
            ->get();

        $fdate = date("Y-m-d 00:00:00", strtotime("first day of previous month"));
        $ldate = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
        $fdate = strtotime($fdate);
        $ldate = strtotime($ldate);
        $fetch_previous_total_customers = DB::table('customers')
            ->select(DB::raw('count(customers.id) as previous_total_customers'))
            ->whereBetween('customers.created_at', [$fdate, $ldate])
            ->get();

        $response['total_customers'] = $fetch_total_customers;
        $response['previous_total_customers'] = $fetch_previous_total_customers;
        echo json_encode($response);
    }

    public function fetch_leatest_order()
    {
        $fetch_leatest_order = DB::table('orders')
            ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
            ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
            ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
            ->select('orders.id', 'pin_wise_village.village_name', 'pin_code.pincode', 'orders.total_payable_amount')
            ->limit(5)
            ->orderBy('orders.ordered_on', 'desc')
            ->get();
        return json_encode($fetch_leatest_order);
    }
    //fetch_orders_status_today
    public function fetch_orders_status_today()
    {
        date_default_timezone_set("Asia/Calcutta");
        $start = date("Y-m-d 00:00:00");
        $start = strtotime($start);
        $end = date("Y-m-d 23:59:59");
        $end = strtotime($end);
        $fetch_orders_status_today = DB::table('orders')
            ->leftJoin('order_status_details', 'order_status_details.id', '=', 'orders.status_id')
            ->select(DB::raw('count(orders.id) as total_orders,
         sum(case when orders.status_id=1 then 1 else 0 end) as active,
        sum(case when orders.status_id=2 then 1 else 0 end) as ready_to_deliver,
        sum(case when orders.status_id=3 then 1 else 0 end) as out_for_delivery,
        sum(case when orders.status_id=4 then 1 else 0 end) as delivered,
        sum(case when orders.status_id=5 then 1 else 0 end) as canceled,
        sum(case when orders.status_id=6 then 1 else 0 end) as rejected'))
         ->whereBetween('orders.last_status_timestamp', [$start, $end])
            ->get();
        return json_encode($fetch_orders_status_today);
    }
    //fetch_orders_status_monthly
    public function fetch_orders_status_monthly()
    {
        date_default_timezone_set("Asia/Calcutta");
        $start = date("Y-m-1 00:00:00");
        $start = strtotime($start);
        $end = date("Y-m-d H:i:s");
        $end = strtotime($end);
        $fetch_orders_status_monthly = DB::table('orders')
            ->leftJoin('order_status_details', 'order_status_details.id', '=', 'orders.status_id')
            ->select(DB::raw('count(orders.id) as total_orders,
         sum(case when orders.status_id=1 then 1 else 0 end) as active,
        sum(case when orders.status_id=2 then 1 else 0 end) as ready_to_deliver,
        sum(case when orders.status_id=3 then 1 else 0 end) as out_for_delivery,
        sum(case when orders.status_id=4 then 1 else 0 end) as delivered,
        sum(case when orders.status_id=5 then 1 else 0 end) as canceled,
        sum(case when orders.status_id=6 then 1 else 0 end) as rejected'))
            ->whereBetween('orders.last_status_timestamp', [$start, $end])
            ->get();
        return json_encode($fetch_orders_status_monthly);
    }
    //fetch_top_selling_products
    public function fetch_top_selling_products()
    {
        date_default_timezone_set("Asia/Calcutta");
        $start = date("Y-m-1 00:00:00");
        $start = strtotime($start);
        $end = date("Y-m-d H:i:s");
        $end = strtotime($end);
        $fetch_top_selling_products = DB::table('orders_details')
            ->leftJoin('product', 'product.id', '=', 'orders_details.product_id')
            ->leftjoin('orders', 'orders.id', '=', 'orders_details.order_id')
            ->select(DB::raw('orders_details.product_id,sum(case when orders_details.product_id then orders_details.quantity else 0 end) as total_qty,product.name as products_name,sum(case when orders_details.product_id then orders_details.per_qty_sell_price else 0 end) as total_amount'))
            ->groupBy('orders_details.product_id', 'product.name')
            ->where('orders.status_id','=',4)
            ->limit(5)
            ->orderBy('total_qty', 'desc')
        // ->whereBetween('orders.ordered_on', [$start, $end])
            ->get();
        return json_encode($fetch_top_selling_products);
    }

    //fetch_pin_wise_order

    public function fetch_pin_wise_order()
    {
        date_default_timezone_set("Asia/Calcutta");
        $start = date("Y-m-1 00:00:00");
        $start = strtotime($start);
        $end = date("Y-m-d H:i:s");
        $end = strtotime($end);
        $fetch_pin_wise_order = DB::table('orders')
            ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
            ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
            ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
            ->select(DB::raw('count( orders.id ) as pin_wise_total_orders,pin_code.pincode,sum(orders.total_payable_amount) as order_amount'))
            ->groupBy('pin_code.id', 'pin_code.pincode')
            ->whereBetween('orders.last_status_timestamp', [$start, $end])
            ->get();
        // LOG::info(json_encode($fetch_pin_wise_order));
        return json_encode($fetch_pin_wise_order);
    }

    //fetch_day_wise_orders
    public function fetch_day_wise_orders()
    {
        $response = array();
        date_default_timezone_set("Asia/Calcutta");
        $start = date("Y-m-1");
        $end = date("Y-m-d");
        $format = 'd-m-Y';
        $dates = array();
        $current = strtotime($start);
        $end = strtotime($end);
        $stepVal = '+1 day';
        while ($current <= $end) {
            $dates[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        $final_list = array();
        foreach ($dates as $date) {
            $start_date = strtotime($date . "00:00:00");
            $end_date = strtotime($date . "23:59:59");
            $final_list = DB::table('orders')
                ->select(DB::raw('count(orders.id) as total_orders'))
                ->whereBetween('orders.last_status_timestamp', [$start_date, $end_date])
                ->first();
            $final_list->date = $date;
            array_push($response, $final_list);
        }
        echo json_encode($response);
    }
    //fetch_sales_chart_data
    public function fetch_sales_chart_data()
    {
        $response = array();
        date_default_timezone_set("Asia/Calcutta");
        $start = date("Y-01-01");
        $end = date("Y-12-31");
        $format = 'Y-m';
        $months = array();
        $current = strtotime($start);
        $end = strtotime($end);
        $stepVal = '+1 month';
        while ($current <= $end) {
            $months[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        $final_list = array();
        foreach ($months as $month) {
            $fdate = date($month . "-d 00:00:00", strtotime("first day of this  month"));
            $start = strtotime($fdate);
            $ldate = date("Y-m-t 23:59:59", strtotime(date($fdate)));
            $end = strtotime($ldate);
            $final_list = DB::table('orders')
                ->select(DB::raw('count(orders.id) as total_orders,sum(orders.total_payable_amount) as total_order_amount'))
                ->whereBetween('orders.last_status_timestamp', [$start, $end])
                ->first();
            $final_list->month = $month;
            array_push($response, $final_list);
        }
        echo json_encode($response);
    }

    //fetch_customer_chart_data

    public function fetch_customer_chart_data()
    {
        $response = array();
        date_default_timezone_set("Asia/Calcutta");
        $start = date("Y-01-01");
        $end = date("Y-12-31");
        $format = 'Y-m';
        $months = array();
        $current = strtotime($start);
        $end = strtotime($end);
        $stepVal = '+1 month';
        while ($current <= $end) {
            $months[] = date($format, $current);
            $current = strtotime($stepVal, $current);
        }
        $final_list = array();
        foreach ($months as $month) {
            $fdate = date($month . "-d 00:00:00", strtotime("first day of this  month"));
            $start = strtotime($fdate);
            $ldate = date("Y-m-t 23:59:59", strtotime(date($fdate)));
            $end = strtotime($ldate);
            $final_list = DB::table('customers')
                ->select(DB::raw('count(customers.id) as total_customers'))
                ->whereBetween('customers.created_at', [$start, $end])
                ->first();
            $final_list->month = $month;
            array_push($response, $final_list);
        }
        echo json_encode($response);
    }

    //fetch_customer_feedback

    public function fetch_customer_feedback(Request $request)
    {

        $columns = array(
            0 => 'customers.name',
            1 => 'customer_feedback.subject',
            2 => 'customer_feedback.submitted_on',
            3 => 'customer_feedback.is_replied',
            4 => 'action',
        );

        $totalData = DB::table('customer_feedback')->count();
        $totalFiltered = $totalData;
        $limit = $request->input('length');
        $start = $request->input('start');
        $ordering = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $customer_feedbacks = DB::table('customer_feedback')
            ->leftJoin('customers', 'customers.id', '=', 'customer_feedback.customer_id')
            ->select('customer_feedback.id as customer_feedback_id', 'customers.name as customer_name', 'customer_feedback.subject', 'customer_feedback.details', 'customer_feedback.submitted_on', 'customer_feedback.is_replied');

        $totalFiltered = $customer_feedbacks->count();
        $customer_feedbacks = $customer_feedbacks
            ->offset($start)
            ->limit($limit)
            ->orderBy($ordering, $dir)
            ->get();
        $data = array();
        if (!empty($customer_feedbacks)) {
            foreach ($customer_feedbacks as $customer_feedback) {
                date_default_timezone_set("Asia/Calcutta");
                $timestamp = $customer_feedback->submitted_on;
                $nestedData['customer'] = $customer_feedback->customer_name;
                $nestedData['subject'] = $customer_feedback->subject;
                $nestedData['date'] = date("d-m-Y h:i  A", $timestamp);
                if ($customer_feedback->is_replied == 1) {
                    $status = '<span class="text-success">Replied</span>';
                } else {
                    $status = '<span class="text-danger">Not replied</span>';
                }
                $nestedData['status'] = $status;
                $nestedData['action'] = '<button class="ui icon button mini primary" data-toggle="modal" data-target="#view_feedback_details_modal' . $customer_feedback->customer_feedback_id . '" id="btn_view_feedback_deails' . $customer_feedback->customer_feedback_id . '" onclick="view_feedback_details(' . $customer_feedback->customer_feedback_id . ')">
                        Details
                    </button>';
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return json_encode($json_data);
    }

    //fetch_feedback_details

    public function fetch_feedback_details(Request $req)
    {
        $customer_feedback_details = DB::table('customer_feedback')
            ->leftJoin('customers', 'customers.id', '=', 'customer_feedback.customer_id')
            ->select('customer_feedback.id as customer_feedback_id', 'customers.name as customer_name', 'customers.email as customer_email', 'customer_feedback.subject', 'customer_feedback.details', 'customer_feedback.submitted_on', 'customer_feedback.is_replied', 'customer_feedback.reply_message')
            ->where('customer_feedback.id', '=', $req->feedback_id)
            ->get();

        return json_encode($customer_feedback_details);
    }

    public function update_reply_msg(Request $req)
    {
        $response = array();
        $update_replied_msg = DB::table('customer_feedback')
            ->where('customer_feedback.id', '=', $req->feedback_id)
            ->update(['reply_message' => $req->replied_msg, 'is_replied' => 1]);
        if ($update_replied_msg) {
            //email
            $USER_EMAIL = $req->customer_email;
            $USER_NAME = $req->customer_name;
            $MAIL_SUBJECT = 'Feedback Reply';
            $MAIL_BODY = $req->replied_msg;

            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
            $response['success'] = 1;
            $response['message'] = "Replied successfully";
            return json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "Something went wrong..!";
            return json_encode($response);
        }
    }
}
