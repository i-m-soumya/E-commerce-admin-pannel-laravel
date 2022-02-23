<?php
namespace App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class Notify
{
    public function notifyUser($user_id,$details,$action,$action_keyword)
    {
        DB::table('user_notification')
        ->insert([
            'user_id'=>$user_id,
            'details'=>$details,
            'action'=>$action,
            'action_keyword'=>$action_keyword,
            'timestamp'=>time(),

        ]);
    }
    public function notifyAggregator($aggregator_id,$details,$action,$action_keyword)
    {
        DB::table('delivery_partner_notification')
        ->insert([
            'aggregator_id'=>$aggregator_id,
            'details'=>$details,
            'action'=>$action,
            'action_keyword'=>$action_keyword,
            'timestamp'=>time(),

        ]);
    }
}
