<?php

namespace App\Http\Controllers\Android;

use App\Classes\AndroidResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OfferAndDeliveryChargeController extends Controller
{
    public function fetch_coupon()
    {
        $androidResponse = new AndroidResponse();
        try {
            $code = DB::table('discount_coupon')
                    ->get();
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Coupon Found!'),
                'coupon' => $code,
            ]);
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/OfferAndDeliveryChargeController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    public function fetch_delivery_charge()
    {
        $androidResponse = new AndroidResponse();
        try {
            $charge = DB::table('delivery_charge')
                    ->first();
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Delivery Charge Found!'),
                'charge' => $charge,
            ]);
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/OfferAndDeliveryChargeController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
}
