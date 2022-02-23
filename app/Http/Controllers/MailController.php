<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\GeneralEmail;

// THIS FILE IS FOR REFERENCE OF MAIL, WILL BE DELETED.

//NEEDS TO  IMPORT THIS 2
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendemail()
    {

        //THIS IS USED TO SEND REQUEST ON MAILER
        $USER_EMAIL = 'deban16189251@gmail.com';
        $USER_NAME = 'Debanjan Baidya';
        $MAIL_SUBJECT = 'This is a very serious email!';
        $MAIL_BODY = 'I told you this is a serious email! But you are not giving attention. Whats wrong with you?';

        Mail::send(
            new GeneralEmail($USER_EMAIL, $USER_NAME, $MAIL_SUBJECT, $MAIL_BODY)
        );
    }
}
