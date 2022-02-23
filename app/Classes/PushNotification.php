<?php
namespace App\Classes;

use Illuminate\Support\Facades\DB;

class PushNotification
{
    private $API_KEY = 'AAAAYFdkeUs:APA91bHc-QPZIQS2RfopjUZMzTL9DC-phayMhxTWVcyp6KZvWnROf3tW5rm61XFsrbMMK25-Y4TuyMBk8n-49wgyGfTBDs-YH7BWWhPLEeGbNoG9JO_HCAG3REF80s3wVLoFKKlqzhCH';
    public function sendPush($user_id, $user_type, $message, $title, $payload)
    {
        $target = array();
        $fcm_tokens = DB::table('fcm_token')
            ->where('fcm_token.user_id', $user_id)
            ->where('fcm_token.user_type', $user_type)
            ->get();
        if (count($fcm_tokens)) {
            foreach ($fcm_tokens as $fcm_token) {
                array_push($target, $fcm_token->token);
            }
        }
        $url = 'https://fcm.googleapis.com/fcm/send';
        $api_key = $this->API_KEY;
        $fields = array();
        $fields['priority'] = "high";
        // $fields['notification'] = ["title" => $title,
        //     "body" => $message,
        //     'notification' => ['message' => $message],
        //     "sound" => "default"];
        $fields['data'] = [
            "title" => $title,
            "message" => $message,
            'notification' => ['message' => $message],
            "payload" => $payload,
            "sound" => "default"
        ];
        if (is_array($target)) {
            $fields['registration_ids'] = $target;
        } else {
            $fields['to'] = $target;
        }
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key=' . $api_key,
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
}
