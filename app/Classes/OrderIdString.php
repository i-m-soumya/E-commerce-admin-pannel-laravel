<?php
namespace App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class OrderIdString
{
    public function get_str($ordered_on,$order_id)
    {
        date_default_timezone_set("Asia/Calcutta");
        $timestamp = $ordered_on;
        $final_date_time = date("mdY", $timestamp);
        $final_string='OD' . $final_date_time . $order_id ;
        return $final_string;
    }
}
