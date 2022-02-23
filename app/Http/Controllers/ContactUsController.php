<?php

namespace App\Http\Controllers;

use App\Mail\GeneralEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    public function contact_us(Request $req)
    {
        $USER_EMAIL = "support@grocerbee.co.in";
        $USER_NAME = "Admin";
        $MAIL_SUBJECT = $req->contact_us_subject;
        $MAIL_BODY = "Name:".$req->contact_us_name.", Email:".$req->contact_us_email.", Message:".$req->contact_us_details;

        Mail::send(
            new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
        );

        $response['success'] = 1;
        $response['message'] = "Mail send successfully";
        return json_encode($response);
    }
}
