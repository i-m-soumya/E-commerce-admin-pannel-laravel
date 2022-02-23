<?php

namespace App\Http\Controllers;

use App\Mail\GeneralEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Session;

class User extends Controller
{
    public function fetch_customer(Request $request)
    {
        $columns = array(
            // 0 => "sl_no",
            0 => 'customers.id',
            1 => 'customers.name',
            2 => 'customers.email',
            3 => 'customers.mobile_number',
            4 => 'customers.created_at',
            5 => 'action',
        );
        $totalData = DB::table('customers')->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $ordering = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $all_customers = DB::table('customers')
            ->select('customers.id', 'customers.name', 'customers.email', 'customers.mobile_number', 'customers.created_at');
            if (!empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $all_customers = $all_customers
                    ->whereRaw("(customers.name LIKE '%{$search}%'
                        OR customers.id LIKE '%{$search}%'
                        OR customers.email LIKE '%{$search}%'
                        OR customers.mobile_number LIKE '%{$search}%'
                        )");

            }

        $totalFiltered = $all_customers->count();
        $all_customers = $all_customers
            ->offset($start)
            ->limit($limit)
            ->orderBy($ordering, $dir)
            ->get();

        $data = array();
        if (!empty($all_customers)) {
            $sl_no = 1;
            foreach ($all_customers as $customer) {
                date_default_timezone_set("Asia/Calcutta");
                $timestamp = $customer->created_at;
                $final_date_time = date("d-m-Y h:i  A", $timestamp);
                $nestedData['sl_no'] = $sl_no;
                $nestedData['customer_id'] = $customer->id;
                $nestedData['customer_name'] = $customer->name;
                $nestedData['customer_email'] = $customer->email;
                $nestedData['customer_mobile'] = $customer->mobile_number;
                $nestedData['created_at'] = $final_date_time;
                $nestedData['action'] = '<button class="btn btn-info btn-sm" data-toggle="modal" data-target="#view_customer_details_modal' . $customer->id . '" onclick="view_customer_details(' . $customer->id . ')">Details</button>';
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
            return json_encode($json_data);
        }
    }
    public function fetch_customer_details(Request $req)
    {
        $fetch_customer_details = DB::table('customers')
        ->leftjoin('orders', 'orders.ordered_by', '=', 'customers.id')
            ->select(DB::raw("count(case when orders.status_id = '4' then 1 end) as total_order,customers.id,customers.name, customers.email, customers.mobile_number, customers.created_at"))
            ->groupBy('customers.name', 'customers.id', 'customers.email', 'customers.mobile_number', 'customers.created_at')
            ->where('customers.id', '=', $req->customer_id)
            ->first();
        $fetch_customer_details->address = DB::table('customer_address')
            ->leftjoin('pin_wise_village', 'customer_address.village_id', '=', 'pin_wise_village.id')
            ->select('customer_address.customer_id', 'customer_address.city', 'customer_address.village_id',
                'customer_address.pin_code', 'customer_address.house_no', 'customer_address.area', 'customer_address.landmark', 'customer_address.state', 'customer_address.country',
                'pin_wise_village.village_name')
            ->where('customer_address.customer_id', '=', $req->customer_id)
            ->get();
        return json_encode($fetch_customer_details);
    }
    //add admin
    public function add_admin(Request $req)
    {
        $email = strtolower($req->admin_email);
        $response = array();
        $checking = DB::table("admin")
            ->where("admin.email", "=", $email)
            ->where("admin.is_deleted", "=", 0)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Admin already available";
            echo json_encode($response);
        } else {
            $chars = "abcdefghijklmnopqrstuvwxyz_0123456789";
            $password = substr(str_shuffle($chars), 0, 6);
            $hashed_password = Hash::make($password);
            $insert_new_admin = DB::table('admin')
                ->insert([
                    'name' => $req->admin_name,
                    'email' => $email,
                    'mobile_number' => $req->admin_mobile,
                    'password' => $hashed_password,
                    'added_by' => Session::get('user')['id'],
                    'admin_type_id' => '1',
                    'is_deleted' => '0',
                    'created_at' => time(),

                ]);
            if ($insert_new_admin) {
                //send_email
                $USER_EMAIL = $email;
                $USER_NAME = $req->admin_name;
                $MAIL_SUBJECT = 'Your account created successfully';
                $MAIL_BODY = 'Your Login password is : ' . $password;

                Mail::send(
                    new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
                );
                $response['success'] = 1;
                $response['message'] = "Admin added successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something went worng";
                echo json_encode($response);
            }
        }
    }
    //add aggregator
    public function add_aggregator(Request $req)
    {
        $email = strtolower($req->aggregator_email);
        $response = array();
        $checking = DB::table("delivery_partner")
            ->where("delivery_partner.email", "=", $email)
            ->where("delivery_partner.is_deleted", "=", 0)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Aggregator already available";
            echo json_encode($response);
        } else {
            $chars = "abcdefghijklmnopqrstuvwxyz_0123456789";
            $password = substr(str_shuffle($chars), 0, 6);
            $hashed_password = hash('sha256', $password);
            $insert_new_aggregator = DB::table('delivery_partner')
                ->insert([
                    'name' => $req->aggregator_name,
                    'email' => $email,
                    'mobile_number' => $req->aggregator_mobile,
                    'password' => $hashed_password,
                    'added_by' => Session::get('user')['id'],
                    'is_deleted' => '0',
                    'created_at' => time(),

                ]);
            if ($insert_new_aggregator) {
                //send_email
                $USER_EMAIL = $email;
                $USER_NAME = $req->admin_name;
                $MAIL_SUBJECT = 'Your account created successfully';
                $MAIL_BODY = 'Your Login password is : ' . $password;

                Mail::send(
                    new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
                );
                $response['success'] = 1;
                $response['message'] = "Aggregator added successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something went worng";
                echo json_encode($response);
            }
        }
    }
    //add salesman
    public function add_salesman(Request $req)
    {
        $email = strtolower($req->salesman_email);
        $response = array();
        $checking = DB::table("admin")
            ->where("admin.email", "=", $email)
            ->where("admin.is_deleted", "=", 0)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Salesman already available";
            echo json_encode($response);
        } else {
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789!_";
            $password = substr(str_shuffle($chars), 0, 6);
            $hashed_password = Hash::make($password);
            $insert_new_salesman = DB::table('admin')
                ->insert([
                    'name' => $req->salesman_name,
                    'email' => $email,
                    'mobile_number' => $req->salesman_mobile,
                    'password' => $hashed_password,
                    'added_by' => Session::get('user')['id'],
                    'admin_type_id' => '2',
                    'is_deleted' => '0',
                    'created_at' => time(),

                ]);
            if ($insert_new_salesman) {
                //send_email
                $USER_EMAIL = $email;
                $USER_NAME = $req->salesman_name;
                $MAIL_SUBJECT = 'Your account created successfully';
                $MAIL_BODY = 'Your Login password is : ' . $password;

                Mail::send(
                    new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
                );
                $response['success'] = 1;
                $response['message'] = "Salesman added successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something went worng";
                echo json_encode($response);
            }
        }
    }
    //fetch admin
    public function fetch_admin()
    {
        $fetch_all_admin = DB::table('admin')
            ->select('admin.id', 'admin.name', 'admin.email', 'admin.mobile_number', 'admin.created_at', 'admin.added_by', 'admin.is_deleted')
            ->where('admin.admin_type_id', '=', '1')
            ->get();
        return $fetch_all_admin;
    }
    //fetch aggregator
    public function fetch_aggregator()
    {
        $fetch_all_aggregator = DB::table('delivery_partner')
            ->select('delivery_partner.id', 'delivery_partner.name', 'delivery_partner.email', 'delivery_partner.mobile_number', 'delivery_partner.created_at', 'delivery_partner.is_deleted')
            ->get();
        return $fetch_all_aggregator;
    }
    //fetch salesman
    public function fetch_salesman()
    {
        $fetch_all_salesman = DB::table('admin')
            ->select('admin.id', 'admin.name', 'admin.email', 'admin.mobile_number', 'admin.created_at', 'admin.is_deleted')
            ->where('admin.admin_type_id', '=', '2')
            ->get();
        return $fetch_all_salesman;
    }
    //change_admin_status
    public function change_admin_status(Request $req)
    {
        if ($req->admin_id == Session::get('user')['id']) {
            $response['success'] = 0;
            $response['message'] = "You cannot diactived own account!";
            return json_encode($response);
        }
        $response = array();
        $email = strtolower($req->admin_email);
        $change_admin_status = DB::table('admin')
            ->where('admin.id', '=', $req->admin_id)
            ->update(['is_deleted' => $req->status]);
        //send_email
        $USER_EMAIL = $email;
        $USER_NAME = $req->admin_name;
        $MAIL_SUBJECT = 'Your account is Deactivated';
        $MAIL_BODY = 'Your account is Deactivated,Kindly contact with admin for more information.';

        Mail::send(
            new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
        );
        if ($change_admin_status) {
            $response['success'] = 1;
            $response['message'] = "Status updated successfully";
            return json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            return json_encode($response);
        }
    }
    //change_aggregator_status
    public function change_aggregator_status(Request $req)
    {
        $response = array();
        $email = strtolower($req->aggregator_email);
        $change_aggregator_status = DB::table('delivery_partner')
            ->where('delivery_partner.id', '=', $req->aggregator_id)
            ->update(['is_deleted' => $req->status]);
        if ($change_aggregator_status) {
            $USER_EMAIL = $email;
            $USER_NAME = $req->aggregator_name;
            $MAIL_SUBJECT = 'Your account is Deactivated';
            $MAIL_BODY = 'Your account is Deactivated,Kindly contact with admin for more information.';
            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
            $response['success'] = 1;
            $response['message'] = "Status updated successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }
    //change_salesman_status
    public function change_salesman_status(Request $req)
    {
        $response = array();
        $email = strtolower($req->salesman_email);
        $change_salesman_status = DB::table('admin')
            ->where('admin.id', '=', $req->salesman_id)
            ->update(['is_deleted' => $req->status]);
        if ($change_salesman_status) {
            $USER_EMAIL = $email;
            $USER_NAME = $req->salesman_name;
            $MAIL_SUBJECT = 'Your account is Deactivated';
            $MAIL_BODY = 'Your account is Deactivated,Kindly contact with admin for more information.';
            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
            $response['success'] = 1;
            $response['message'] = "Status updated successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }

    //reset_admin_password
    public function reset_admin_password(Request $req)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr(str_shuffle($chars), 0, 6);
        $hashed_password = Hash::make($password);
        $update_admin_pass = DB::table('admin')
            ->where('admin.id', '=', $req->admin_id)
            ->update(['password' => $hashed_password]);
        if ($update_admin_pass) {
            //send_email
            $USER_EMAIL = $req->admin_email;
            $USER_NAME = $req->admin_name;
            $MAIL_SUBJECT = 'Password reseted successfully';
            $MAIL_BODY = 'Your new Login password is : ' . $password;

            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
            $response['success'] = 1;
            $response['message'] = "Password reseted successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }
    //reset_aggregator_password
    public function reset_aggregator_password(Request $req)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr(str_shuffle($chars), 0, 6);
        $hashed_password = Hash::make($password);
        $update_aggregator_pass = DB::table('delivery_partner')
            ->where('delivery_partner.id', '=', $req->aggregator_id)
            ->update(['password' => $hashed_password]);
        if ($update_aggregator_pass) {
            //send_email
            $USER_EMAIL = $req->aggregator_email;
            $USER_NAME = $req->aggregator_name;
            $MAIL_SUBJECT = 'Password reseted successfully';
            $MAIL_BODY = 'Your new Login password is : ' . $password;

            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
            $response['success'] = 1;
            $response['message'] = "Password reseted successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }

    //reset_salesman_password
    public function reset_salesman_password(Request $req)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr(str_shuffle($chars), 0, 6);
        $hashed_password = Hash::make($password);
        $update_salesman_pass = DB::table('admin')
            ->where('admin.id', '=', $req->salesman_id)
            ->update(['password' => $hashed_password]);
        if ($update_salesman_pass) {
            //send_email
            $USER_EMAIL = $req->salesman_email;
            $USER_NAME = $req->salesman_name;
            $MAIL_SUBJECT = 'Password reseted successfully';
            $MAIL_BODY = 'Your new Login password is : ' . $password;

            Mail::send(
                new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
            );
            $response['success'] = 1;
            $response['message'] = "Password reseted successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }
}
