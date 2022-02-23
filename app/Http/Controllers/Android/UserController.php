<?php

namespace App\Http\Controllers\Android;

use App\Classes\AndroidResponse;
use App\Classes\PushNotification;
use App\Classes\Extras;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \stdClass;

class UserController extends Controller
{
    public function validate_phone_number_and_password(Request $request)
    {
        try {
            $status = new stdClass();
            $customer_check_user = DB::table('customers')
                ->where('mobile_number', $request->phone_number)
                ->select('id')
                ->first();
            if (!$customer_check_user) {
                $status->status = 0;
                $status->message = 'You do not have an account created to log in. Create one now.';
                return response()->json([
                    'status' => $status,
                    'csrf_token' => csrf_token(),
                ]);
            }

            $customer = DB::table('customers')

                ->where('mobile_number', $request->phone_number)

                ->where('password', $request->password)

                ->select('id')

                ->first();

            if ($customer) {
                $status->status = 1;

                $status->message = 'You have successfully logged in to your account.';

                return response()->json([

                    'status' => $status,

                    'csrf_token' => csrf_token(),

                ]);
            } else {
                $status->status = 0;

                $status->message = 'Your password seems to be invalid.';

                return response()->json([

                    'status' => $status,

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            $status->status = 0;

            $status->message = 'Oops. Something went wrong!';

            return response()->json([

                'status' => $status,

            ]);
        }
    }

    public function get_user_details(Request $request)
    {
        try {
            $status = new stdClass();

            $customer = DB::table('customers')

                ->where('mobile_number', $request->phone_number)

                ->select('id', 'name', 'email', 'mobile_number', 'profile_image_upload_id', 'created_at', 'last_active','referral_code')

                ->first();

            if ($customer) {
                $customer->profile_image = DB::table('uploads')

                    ->where('id', $customer->profile_image_upload_id)

                    ->where('is_deleted', 0)

                    ->select('id', 'file_name', 'url')

                    ->first();

                $customer->address_details = DB::table('customer_address')

                    ->where('customer_id', $customer->id)

                    ->leftJoin('pin_wise_village', 'pin_wise_village.id', '=', 'customer_address.village_id')

                    ->select('customer_address.id', 'customer_id', 'city', 'village_id', 'village_name', 'customer_address.pin_code', 'house_no', 'area', 'landmark', 'state', 'country')

                    ->get();

                $status->status = 1;

                $status->message = 'We are able to find users details in our records.';

                return response()->json([

                    'status' => $status,

                    'customer_details' => $customer,

                ]);
            } else {
                $status->status = 0;

                $status->message = 'We are unable to find it in our records.';

                return response()->json([

                    'status' => $status,

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            $status->status = 0;

            $status->message = 'Oops. Something went wrong!';

            return response()->json([

                'status' => $status,

            ]);
        }
    }

    public function set_password(Request $request)
    {
        $androidResponse = new AndroidResponse();
        $extras = new Extras();
        $status = new stdClass();

        if ($request->password == '' || $request->phone_number == '') {
            $status->status = 0;

            $status->message = 'Please enter a value.';

            return response()->json([

                'status' => $status,

            ]);
        }

        try {
            $customer = DB::table('customers')

                ->where('mobile_number', $request->phone_number)

                ->select('id')

                ->first();

            if ($customer) {
                $check_password = DB::table('customers')
                    ->where('mobile_number', '=' , $request->phone_number)
                    ->where('password', '=', $request->password)
                    ->first();
                if ($check_password) {
                    return response()->json([
                        'status' => $androidResponse->getStatus(0, 'Please enter a different password!'),
                    ]);
                }
                $affected_customer = DB::table('customers')

                    ->where('id', $customer->id)

                    ->update(['password' => $request->password]);

                if ($affected_customer) {
                    $status->status = 1;

                    $status->message = 'You have successfully updated your password.';

                    return response()->json([

                        'status' => $status,
                        'is_new' => 0,
                        'is_referal_active' => $extras->isReferalActive(),

                    ]);
                }
            } else {
                $flag = 0;
                $referal_code = '';
                while($flag == 0 && $referal_code == '') {
                    $referal_code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 5);
                    $referal_code_is_exist = DB::table('customers')
                        ->where('referral_code', $referal_code)
                        ->select('referral_code')
                        ->first();
                    if(!$referal_code_is_exist) {
                        $flag = 1;
                    } else {
                        $flag = 0;
                        $referal_code = '';
                    }
                }
                Log::debug($flag . $referal_code);
                $new_customer = DB::table('customers')
                    ->insertGetId(
                        [
                            'mobile_number' => $request->phone_number,
                            'is_deleted' => '0',
                            'password' => $request->password,
                            'created_at' => time(),
                            'last_active' => time(),
                            'referral_code' => $referal_code,
                        ]
                    );
                if ($new_customer) {
                    $status->status = 1;
                    $status->message = 'New user has been created.';
                    return response()->json([
                        'status' => $status,
                        'is_new' => 1,
                        'is_referal_active' => $extras->isReferalActive(),
                        'customer_id' => $new_customer,
                    ]);
                }
            }

            $status->status = 0;

            $status->message = 'Failed to update Password.';

            return response()->json([

                'status' => $status,

            ]);
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            $status->status = 0;

            $status->message = 'Oops. Something went wrong!';

            return response()->json([

                'status' => $status,

            ]);
        }
    }

    public function fetchVillageFromPin(Request $request)
    {
        $androidResponse = new AndroidResponse();

        if (!$request->pin_code || $request->pin_code == '') {
            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Please enter a pin code!'),

            ]);
        }

        try {
            $village = DB::table('pin_wise_village')

                ->join('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')

                ->where('pin_code.pincode', '=', $request->pin_code)

                ->select('pin_wise_village.id as village_id', 'village_name', 'pin_code.id as pincode_id', 'pincode')

                ->get();

            if (count($village)) {
                return response()->json([

                    'status' => $androidResponse->getStatus(1, 'Pincode mached with our records!'),

                    'villages' => $village,

                ]);
            } else {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! No records found.'),

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }

    public function deleteAddress(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->user_id || !$request->address_id) {
            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),

            ]);
        }
        try {
            $address_delete = DB::table('customer_address')
                ->where('customer_id', '=', $request->user_id)
                ->where('id', '=', $request->address_id)
                ->delete();
            if ($address_delete) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Address Deleted.'),
                    ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! No records found.'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }
    public function addAddress(Request $request)
    {
        $androidResponse = new AndroidResponse();

        if (!$request->pin_code || !$request->pin_code || !$request->pin_code) {
            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),

            ]);
        }

        try {
            $address = DB::table('customer_address')

                ->insert(
                    [

                        'customer_id' => $request->customer_id,

                        'city' => $request->city,

                        'village_id' => $request->village_id,

                        'pin_code' => $request->pin_code,

                        'house_no' => $request->house_no,

                        'area' => $request->area,

                        'landmark' => $request->landmark,

                        'state' => $request->state,

                        'country' => $request->country,

                    ]
                );

            if ($address) {
                return response()->json([

                    'status' => $androidResponse->getStatus(1, 'Address saved.'),

                ]);
            } else {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! failled to save address!'),

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }

    public function updateAddress(Request $request)
    {
        $androidResponse = new AndroidResponse();

        if (!$request->pin_code || !$request->pin_code || !$request->pin_code || !$request->address_id) {
            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),

            ]);
        }
        $extras = new Extras();
        $check_house_no= $extras->check_Str($request->house_no);
        $check_area= $extras->check_Str($request->area);
        $check_landmark= $extras->check_Str($request->landmark);
        $check_state= $extras->check_Str($request->state);
        $check_country= $extras->check_Str($request->country);
        if (!$check_house_no || !$check_area || !$check_landmark || !$check_state || !$check_country) {
            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Unexpected String! Please use English characters and digits.'),

            ]);
        }

        try {
            $address = DB::table('customer_address')

                ->where('id', '=', $request->address_id)

                ->where('customer_id', '=', $request->customer_id)

                ->update(
                    [

                        'city' => $request->city,

                        'village_id' => $request->village_id,

                        'pin_code' => $request->pin_code,

                        'house_no' => $request->house_no,

                        'area' => $request->area,

                        'landmark' => $request->landmark,

                        'state' => $request->state,

                        'country' => $request->country,

                    ]
                );

            if ($address) {
                return response()->json([

                    'status' => $androidResponse->getStatus(1, 'Village updated successfully.'),

                ]);
            } else {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! failled to update village!'),

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }

    public function updateName(Request $request)
    {
        $androidResponse = new AndroidResponse();
        $extras = new Extras();

        if (!$request->user_id || !$request->name) {
            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),

            ]);
        }
        if(!$extras->check_Str($request->name))
        {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Unexpected String! Please use English characters and digits.'),
            ]);
        }

        try {
            $update_name_query = DB::table('customers')

                ->where('id', '=', $request->user_id)

                ->where('is_deleted', '=', 0)

                ->update(
                    [

                        'name' => $request->name,

                    ]
                );

            if ($update_name_query) {
                return response()->json([

                    'status' => $androidResponse->getStatus(1, 'Name updated successfully.'),

                ]);
            } else {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! failled to update Name!'),

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }

    public function updateEmail(Request $request)
    {
        $androidResponse = new AndroidResponse();

        if (!$request->user_id || !$request->email) {
            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),

            ]);
        }

        try {
            $update_email_query = DB::table('customers')

                ->where('id', '=', $request->user_id)

                ->where('is_deleted', '=', 0)

                ->update(
                    [

                        'email' => $request->email,

                    ]
                );

            if ($update_email_query) {
                return response()->json([

                    'status' => $androidResponse->getStatus(1, 'Email updated successfully.'),

                ]);
            } else {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! failled to update Email!'),

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }

    public function updatePassword(Request $request)
    {
        $androidResponse = new AndroidResponse();

        if (!$request->user_id || !$request->password) {
            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),

            ]);
        }

        try {

            $check_password = DB::table('customers')
                ->where('id', '=', $request->user_id)
                ->where('password', '=', $request->password)
                ->first();
            if ($check_password) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Please enter a different password!'),
                ]);
            }

            $update_password_query = DB::table('customers')

                ->where('id', '=', $request->user_id)

                ->update(
                    [

                        'password' => $request->password,

                    ]
                );

            if ($update_password_query) {
                return response()->json([

                    'status' => $androidResponse->getStatus(1, 'Password updated successfully.'),

                ]);
            } else {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! failled to update Email!'),

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }

    public function updatePhone(Request $request)
    {
        $androidResponse = new AndroidResponse();
        $extras = new Extras();

        if (!$request->user_id || !$request->phone_number) {
            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),

            ]);
        }
        if(!$extras->check_Str($request->phone_number))
        {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Unexpected String! Please use English characters and digits.'),
            ]);
        }

        try {
            $check_mobile = DB::table('customers')
                ->where('id', '=', $request->user_id)
                ->where('mobile_number', '=', $request->phone_number)
                ->first();
            if ($check_mobile) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'This phone number already exists.'),
                ]);
            }
            $update_mobile_number_query = DB::table('customers')

                ->where('id', '=', $request->user_id)

                ->where('is_deleted', '=', 0)

                ->update(
                    [

                        'mobile_number' => $request->phone_number,

                    ]
                );

            if ($update_mobile_number_query) {
                return response()->json([

                    'status' => $androidResponse->getStatus(1, 'Phone Number updated successfully.'),

                ]);
            } else {
                return response()->json([

                    'status' => $androidResponse->getStatus(0, 'Sorry! failled to update Phone Number!'),

                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }
    public function fcmTockenUpdate(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->user_id || !$request->token ) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),
            ]);
        }
        try {
            $is_token_exist = DB::table('fcm_token')
                ->where('user_id', '=', $request->user_id)
                ->where('token', '=', $request->token)
                ->where('user_type', '=', $request->user_type)
                ->first();
            if (!$is_token_exist) {
                //INSERT
                $new_token = DB::table('fcm_token')
                    ->insert(
                        [
                            'user_id' => $request->user_id,
                            'token' => $request->token,
                            'user_type' => $request->user_type,
                            'last_active' => time(),
                        ]
                    );
            } else {
                //UPDATE
                $update_token = DB::table('fcm_token')
                    ->where('user_id', '=', $request->user_id)
                    ->where('token', '=', $request->token)
                    ->where('user_type', '=', $request->user_type)
                    ->update(
                        [
                            'last_active' => time(),
                        ]
                    );
            }
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Done!'),
            ]);
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    //fetch_notifications
    public function fetch_notifications(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            if (!$request->user_id) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! User id is required.'),
                ]);
            }
            $fetch_notifications = DB::table('user_notification')
                ->select('*')
                ->where('user_notification.user_id', '=', $request->user_id)
                ->orderBy('user_notification.timestamp', 'desc')
                ->paginate(10);
            if (count($fetch_notifications->toArray()['data'])) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Notification found'),
                    'notifications' => $fetch_notifications->toArray()['data'],
                    'total' => $fetch_notifications->total(),
                    'per_page' => $fetch_notifications->perPage(),
                    'current_page' => $fetch_notifications->currentPage(),
                    'total_page' => $fetch_notifications->lastPage(),
                    'from' => $fetch_notifications->firstItem(),
                    'to' => $fetch_notifications->lastItem(),
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No Notifications found'),
                ]);
            }

        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
    //fetch_faq
    public function fetch_faq(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            if (!$request->user_id) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! User id is required.'),
                ]);
            }
            $fetch_faq = DB::table('customer_feedback')
                ->select('*')
                ->where('customer_feedback.customer_id', '=', $request->user_id)
                ->paginate(10);
            if (count($fetch_faq->toArray()['data'])) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'FAQ found'),
                    'faq_list' => $fetch_faq->toArray()['data'],
                    'total' => $fetch_faq->total(),
                    'per_page' => $fetch_faq->perPage(),
                    'current_page' => $fetch_faq->currentPage(),
                    'total_page' => $fetch_faq->lastPage(),
                    'from' => $fetch_faq->firstItem(),
                    'to' => $fetch_faq->lastItem(),
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No data found'),
                ]);
            }

        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
    public function push(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->title || !$request->message  || !$request->target) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Sorry! User id is required.'),
            ]);
        }
        $API_KEY = 'XXX';
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array();
        $fields['priority'] = "high";
        $fields['notification'] = ["title" => $request->title,
            "body" => $request->message,
            'notification' => ['message' => $request->message],
            "sound" => "default"];
        $fields['data'] = json_decode($request->data) ?? null;
        if (is_array($request->target)) {
            $fields['registration_ids'] = $request->target;
        } else {
            $fields['to'] = $request->target;
        }
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $API_KEY,
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === false) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;

    }

    //fetch_total_order_and_money_saved
    public function fetch_total_order_and_money_saved(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            if (!$request->user_id) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! User id is required.'),
                ]);
            }
            $fetch_total_order_and_money_saved = DB::table('orders')
                ->select(DB::raw('count(orders.id) as total_order,sum(orders.total_discount) as total_money_saved,orders.ordered_by'))
                ->groupBy('orders.ordered_by')
                ->where('orders.ordered_by', '=', $request->user_id)
                ->where('orders.status_id', '=', '4')
                ->first();
            if ($fetch_total_order_and_money_saved) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Data found'),
                    'total_order_and_money_saved' => $fetch_total_order_and_money_saved,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No data found'),
                ]);
            }

        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    //add_feedback_query
    public function add_feedback_query(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            if (!$request->user_id || !$request->subject || !$request->details) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! data should not be null'),
                ]);
            }
            $add_feedback_query = DB::table('customer_feedback')

                ->insert([
                    'customer_id' => $request->user_id,
                    'subject' => $request->subject,
                    'details' => $request->details,
                    'submitted_on' => time(),
                    'is_replied' => 0,
                ]);

            if ($add_feedback_query) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Feedback added'),
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Problem on adding feedback'),
                ]);
            }

        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
    public function addReferalCode(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            if (!$request->applied_code || !$request->user_mobile_no) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! data should not be null'),
                ]);
            }
        $refered_to_user_details = DB::table('customers')
            ->where('mobile_number', $request->user_mobile_no)
            ->first();
        if(!$refered_to_user_details) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Your mobile number is not valid!'),
            ]);
        }
        $refered_by_user_details = DB::table('customers')
            ->where('referral_code', $request->applied_code)
            ->first();
        if(!$refered_by_user_details) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Your coupon code is not valid!'),
            ]);
        }
        DB::table('customers')
            ->where('id', $refered_to_user_details->id)
            ->update(['applied_referal' => $request->applied_code]);
        $referal_details = DB::table('referal_settings')
            ->first();
        DB::table('referal_details')
            ->insertGetId(
                [
                    'referal_to' => $refered_to_user_details->id,
                    'referal_for' => $refered_by_user_details->id,
                    'valid_till' => time() + $referal_details->validity,
                    'is_eligeble' => 0,
                    'is_used' => 0,
                    'used_on' => null,
                    'amount' => $referal_details->referal_amount,
                ]
            );
        return response()->json([
            'status' => $androidResponse->getStatus(1, 'Your coupon code added!'),
        ]);
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
}
