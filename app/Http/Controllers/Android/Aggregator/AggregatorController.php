<?php

namespace App\Http\Controllers\Android\Aggregator;

use App\Classes\AndroidResponse;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AggregatorController extends Controller
{
    //authenticate_aggregator

    public function authenticate_aggregator(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            $aggregator = DB::table('delivery_partner')
                ->where(['email' => $request->email, 'is_deleted' => '0'])
                ->first();
            if (!$aggregator) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Aggregator not found'),
                    'csrf_token' => csrf_token(),
                ]);
            } else if ($request->password != $aggregator->password) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Incorrect password'),
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'You have successfully logged in to your account.'),
                    'aggregator_details' => $aggregator,
                    'csrf_token' => csrf_token(),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/Aggregator/AggregatorController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }

    }

    //get_profile_details

    public function get_profile_details(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            $aggregator_details = DB::table('delivery_partner')
                ->where('delivery_partner.email', '=', $request->email)
                ->where('is_deleted', '=', 0)
                ->select('id', 'name', 'email', 'mobile_number', 'password', 'added_by', 'is_deleted', 'created_at', 'created_at')
                ->first();
            if ($aggregator_details) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Details found in our records.'),
                    'aggregator_details' => $aggregator_details,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Details not found in our records.'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    //update_password

    public function update_password(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->user_id || !$request->password) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please send proper data!'),
            ]);
        }
        try {
            $hashed_password = $request->password;
            $update_password_query = DB::table('delivery_partner')
                ->where('id', '=', $request->user_id)
                ->where('is_deleted', '=', 0)
                ->update(
                    [
                        'password' => $hashed_password,
                    ]
                );
            if ($update_password_query) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Password updated successfully.'),
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Sorry! failled to update Password!'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/UserController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

}
