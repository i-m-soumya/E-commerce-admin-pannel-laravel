<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\GeneralEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfferMailAndPush extends Controller
{
    public function sendMail(Request $request) {
        $users = DB::table('customers')
        ->select('email')
        ->where('email','<>','')
        ->get();
        if(count($users)) {
            foreach ($users as $user) {
                Log::info($user->email);
                $USER_EMAIL = $user->email;
                $USER_NAME = '';
                $MAIL_SUBJECT = 'GROCERBEE মহালয়া সুপার অফার';
                $MAIL_BODY = 'Mahalaya Special Offer
                Get (Slice 1.2L+ 7Up 1.25L+ Thumsup 1L) only for Rs 150. No minimum order Value. Download the app from Google Play Store to get all your groceries at your doorstep.';

                Mail::send(
                    new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
                );
            }
        }

    }
}
