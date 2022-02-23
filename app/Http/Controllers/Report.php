<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDF;
use App\Classes\Extras;
class Report extends Controller
{
    public function fetch_order_by_product(Request $request)
    {
        $columns = array(
            0 => "sl_no",
            1 => 'orders_details.product_id',
            2 => 'product.name',
            3 => 'unit_type_name',
            4 => 'product.quantity',
            5 => 'total_order',
            6 => 'total_quantity_amount',
            7 => 'total_order_amount',
        );
        // $totalData = DB::table('orders_details')->count();
        // $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $ordering = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $all_orders = DB::table('orders_details')
            ->leftJoin('product', 'product.id', '=', 'orders_details.product_id')
            ->leftjoin('orders', 'orders.id', '=', 'orders_details.order_id')
            ->leftJoin('unit_type', 'product.unit_type_id', '=', 'unit_type.id')
            ->select(DB::raw('sum(case when orders_details.product_id then orders_details.quantity else 0 end) as total_order,product.name,orders_details.product_id,product.quantity,unit_type.name as unit_type_name,product.quantity*sum(case when orders_details.product_id then orders_details.quantity else 0 end) as total_quantity_amount,SUM(orders_details.per_qty_sell_price * orders_details.quantity) as total_order_amount'))
            ->where('orders.status_id','=',1)
            ->orwhere('orders.status_id','=',2)
            ->orwhere('orders.status_id','=',3)
            ->orwhere('orders.status_id','=',4);

        if (!empty($request->start_date) && !empty($request->end_date)) {
            date_default_timezone_set("Asia/Calcutta");
            $start_date = strtotime($request->start_date);
            $end_date = strtotime($request->end_date);
            $all_orders = $all_orders
                ->whereBetween('orders.ordered_on', [$start_date, $end_date]);

        }

        $all_orders = $all_orders
            ->groupBy('orders_details.product_id', 'product.name', 'product.quantity', 'unit_type_name')
            //->offset($start)
            //->limit($limit)
            ->orderBy($ordering, $dir)
            ->get();

            $data_count = count($all_orders);
            $totalData = $data_count;

            $fiter_data_array=array();
            $last_limit = $limit+$start;
            if($last_limit > intval($totalData)) {
                $last_limit = intval($totalData);
            }
            for($i=$start;$i<$last_limit ;$i++){
                array_push($fiter_data_array,$all_orders[$i]);
            }


        $totalFiltered = count($fiter_data_array);
        $data = array();
        if (!empty($fiter_data_array)) {
            $sl_no = $start+1;
            foreach ($fiter_data_array as $order) {
                //create key for column value
                $nestedData['sl_no'] = $sl_no;
                $nestedData['product_id'] = $order->product_id;
                $nestedData['product_name'] = $order->name;
                $nestedData['unit_type'] = $order->unit_type_name;
                $nestedData['unit_quantity'] = $order->quantity;
                $nestedData['total_product_order'] = $order->total_order;
                $nestedData['total_unit_quantity'] = $order->total_quantity_amount . $order->unit_type_name;
                $nestedData['total_amount'] = "₹" . number_format($order->total_order_amount);
                $data[] = $nestedData;
                $sl_no++;
            }
            //create_json
            $json_data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalData),
                "data" => $data,
            );
            //Log::info($json_data);
            return json_encode($json_data);
        } else {
            $json_data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => array(),
            );
            return json_encode($json_data);
        }
    }
    public function fetch_aggregator_report(Request $request)
    {
        $columns = array(
            0 => 'orders.id',
            1 => 'delivery_partner.name',
            2 => 'total_order',
            3 => 'total_order_amount',
            4 => 'action',
        );
        $totalData = DB::table('delivery_partner')->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $ordering = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $all_aggregator = DB::table('delivery_partner')
            ->leftJoin('orders', 'orders.assigned_delevery_partner_id', '=', 'delivery_partner.id')
            ->select('delivery_partner.name')
            ->select(DB::raw('count(orders.id) as total_order,sum(orders.total_payable_amount) as total_order_amount,orders.assigned_delevery_partner_id,delivery_partner.name,delivery_partner.id as delivery_partner_id'))
            ->where('orders.status_id', '=', '4');

        if (!empty($request->start_date) && !empty($request->end_date)) {
            date_default_timezone_set("Asia/Calcutta");
            $start_date = strtotime($request->start_date);
            $end_date = strtotime($request->end_date);
            $all_aggregator = $all_aggregator
                ->whereBetween('orders.last_status_timestamp', [$start_date, $end_date]);

        }
        $all_aggregator = $all_aggregator
            ->groupBy('orders.assigned_delevery_partner_id', 'delivery_partner.name', 'delivery_partner.id')
            ->offset($start)
            ->limit($limit)
            // ->orderBy($ordering, $dir)
            ->get();

        $totalFiltered = count($all_aggregator);

        $data = array();
        if (!empty($all_aggregator)) {
            foreach ($all_aggregator as $aggregator) {
                if ($aggregator->total_order_amount == "") {
                    $aggregator->total_order_amount = 0;
                }
                //create key for column value
                $nestedData['id'] = $aggregator->delivery_partner_id;
                $nestedData['name'] = $aggregator->name;
                $nestedData['total_order'] = $aggregator->total_order;
                $nestedData['total_order_amount'] = "₹" . number_format($aggregator->total_order_amount);
                $nestedData['action'] = '<button class="btn btn-primary btn-sm ml-2 " onclick="aggregator_view_order_details(' . $aggregator->delivery_partner_id . ')" data-toggle="modal" data-target="#aggregator_view_order_details_modal' . $aggregator->delivery_partner_id . '" id="btn_view_aggregator_order_details" "> Order details </button>';
                $data[] = $nestedData;
            }
            //create_json
            $json_data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data,
            );
            return json_encode($json_data);
        }
    }
    //fetch_aggregator_order_details
    public function fetch_aggregator_order_details(Request $request)
    {
        $columns = array(
            0 => 'orders.id',
            1 => 'pin_code.pincode',
            2 => 'pin_wise_village.village_name',
            3 => 'orders.total_payable_amount',
            4 => 'orders.ordered_on',
        );
        // $totalData = DB::table('orders')
        //     ->where('orders.assigned_delevery_partner_id', '=', $request->aggregator_id)
        //     ->where('orders.status_id', '=', '4')
        //     ->count();
        // $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $ordering = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $aggregator_order_details = DB::table('orders')
            ->leftJoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
            ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
            ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
            ->select("orders.id as orders_id", "orders.ordered_on","orders.last_status_timestamp", "orders.total_payable_amount",
                'pin_wise_village.village_name', 'pin_code.pincode',

            )
            ->where('orders.assigned_delevery_partner_id', '=', $request->aggregator_id)
            ->where('orders.status_id', '=', '4');

        if (!empty($request->start_date) && !empty($request->end_date)) {
            date_default_timezone_set("Asia/Calcutta");
            $start_date = strtotime($request->start_date);
            $end_date = strtotime($request->end_date);
            $aggregator_order_details = $aggregator_order_details
                ->whereBetween('orders.last_status_timestamp', [$start_date, $end_date]);

        }
        $aggregator_order_details = $aggregator_order_details
            // ->offset($start)
            ->orderBy($ordering, $dir)
            // ->limit($limit)
            ->get();

        // $totalFiltered = count($aggregator_order_details);
        $data_count = count($aggregator_order_details);
        $totalData = $data_count;

        $fiter_data_array=array();
        $last_limit = $limit+$start;
        if($last_limit > intval($totalData)) {
            $last_limit = intval($totalData);
        }
        for($i=$start;$i<$last_limit ;$i++){
            array_push($fiter_data_array,$aggregator_order_details[$i]);
        }


    $totalFiltered = count($fiter_data_array);

        $data = array();
        if (!empty($fiter_data_array)) {
            foreach ($fiter_data_array as $order) {
                if ($order->total_payable_amount == "") {
                    $order->total_payable_amount = 0;
                }
                //create key for column value
                date_default_timezone_set("Asia/Calcutta");
                $timestamp = $order->last_status_timestamp;
                $final_date_time = date("d-m-Y h:i  A", $timestamp);
                $nestedData['id'] = $order->orders_id;
                $nestedData['pincode'] = $order->pincode;
                $nestedData['village_name'] = $order->village_name;
                $nestedData['total_amount'] = "₹" . number_format($order->total_payable_amount);
                $nestedData['date_time'] = $final_date_time;
                $data[] = $nestedData;
            }
            //create_json
            $json_data = array(
                "draw" => intval($request->input('draw')),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalData),
                "data" => $data,
            );
            return json_encode($json_data);
        }
    }
    //print_aggregator report
    public function print_aggregator_report(Request $request)
    {
        $all_aggregator_report_data = DB::table('delivery_partner')
            ->leftJoin('orders', 'orders.assigned_delevery_partner_id', '=', 'delivery_partner.id')
            ->select('delivery_partner.name')
            ->select(DB::raw('count(orders.id) as total_order,sum(orders.total_payable_amount) as total_order_amount,orders.assigned_delevery_partner_id,delivery_partner.name,delivery_partner.id as delivery_partner_id'))
            ->where('orders.status_id', '=', '4');
        if (!empty($request->start_date) && !empty($request->end_date)) {
            date_default_timezone_set("Asia/Calcutta");
            $start_date = strtotime($request->start_date);
            $end_date = strtotime($request->end_date);
            $all_aggregator_report_data = $all_aggregator_report_data
                ->whereBetween('orders.last_status_timestamp', [$start_date, $end_date]);

        }
        $all_aggregator_report_data = $all_aggregator_report_data
            ->groupBy('orders.assigned_delevery_partner_id', 'delivery_partner.name', 'delivery_partner.id')
            ->orderBy('delivery_partner.id', 'asc')
            ->get();
        $timestamp = time();
        $print_date = date("d-m-Y h:i  A", $timestamp);
        $get_logo_link = new Extras();
        $logo_link=$get_logo_link->get_logo_link();
        $pdf = PDF::loadView('pdf.aggregator_report_pdf', ['all_data' => $all_aggregator_report_data, 'start_date' => $request->start_date, 'end_date' => $request->end_date, 'print_date' => $print_date,'logo_link' => $logo_link]);
        return $pdf->stream('aggregator report.pdf');
    }
    //print_order_by_products
    public function print_order_by_product_report(Request $request)
    {
        date_default_timezone_set("Asia/Calcutta");
        $start_date = strtotime($request->start_date);
        $end_date = strtotime($request->end_date);
        $timestamp = time();
        $print_date = date("d-m-Y h:i  A", $timestamp);
        $all_orders = DB::table('orders_details')
        ->leftJoin('product', 'product.id', '=', 'orders_details.product_id')
        ->leftjoin('orders', 'orders.id', '=', 'orders_details.order_id')
        ->leftJoin('unit_type', 'product.unit_type_id', '=', 'unit_type.id')
        ->select(DB::raw('sum(case when orders_details.product_id then orders_details.quantity else 0 end) as total_order,product.name,orders_details.product_id,product.quantity,unit_type.name as unit_type_name,product.quantity*sum(case when orders_details.product_id then orders_details.quantity else 0 end) as total_quantity_amount,SUM(orders_details.per_qty_sell_price * orders_details.quantity) as total_order_amount'))
            ->where('orders.status_id','=',1)
            ->orwhere('orders.status_id','=',2)
            ->orwhere('orders.status_id','=',3)
            ->orwhere('orders.status_id','=',4)
            ->whereBetween('orders.last_status_timestamp', [$start_date, $end_date])
            ->groupBy('orders_details.product_id', 'product.name', 'product.quantity', 'unit_type_name')
            ->get();
            $get_logo_link = new Extras();
        $logo_link=$get_logo_link->get_logo_link();
        $pdf = PDF::loadView('pdf.order_by_product_report_pdf', ['all_data' => $all_orders, 'start_date' => $request->start_date, 'end_date' => $request->end_date, 'print_date' => $print_date,'logo_link' => $logo_link]);
        return $pdf->stream('order_by_product_report.pdf');
    }
    //fetch  pincode
    public function fetch_pincode(Request $request)
    {
        $all_pincode = DB::table('pin_code')
            ->select('pin_code.id as pincode_id', 'pin_code.pincode', 'pin_code.is_active')
            ->get();
        return json_encode($all_pincode);

    }
    //fetch village according to pincode
    public function fetch_village(Request $request)
    {
        $all_village = DB::table('pin_wise_village')
            ->select('pin_wise_village.id as village_id', 'pin_wise_village.village_name')
            ->where('pin_wise_village.pin_code', '=', $request->selected_pincode)
            ->orderby('pin_wise_village.village_name','asc')
            ->get();
        return json_encode($all_village);
    }

    public function data_on_pincode_village(Request $request)
    {
        $all_fiter_data = DB::table('orders')
            ->leftjoin('order_address', 'order_address.id', '=', 'orders.order_address_id')
            ->leftjoin('pin_wise_village', 'pin_wise_village.id', '=', 'order_address.village_id')
            ->select(DB::raw('count(orders.id) as total_order,sum(orders.total_payable_amount) as total_order_amount'))
            ->where('order_address.village_id', '=', $request->selected_village)
            ->where('orders.status_id', '=', '4');
        if (!empty($request->start_date) && !empty($request->end_date)) {
            date_default_timezone_set("Asia/Calcutta");
            $start_date = strtotime($request->start_date);
            $end_date = strtotime($request->end_date);
            $all_fiter_data = $all_fiter_data
                ->whereBetween('orders.last_status_timestamp', [$start_date, $end_date]);

        }
        $all_fiter_data = $all_fiter_data
            ->get();
        return json_encode($all_fiter_data);
    }
}
