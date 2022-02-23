<?php
namespace App\Classes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class Extras
{
    public function get_logo_link()
    {
        $logo_link="http://grocerbee.luciferdev.com/";
        return $logo_link;
    }
    public function check_Str($string)
    {
        $string = str_replace("-", '', $string);
        if (!preg_match('/[^A-Za-z . \/&!@+ 0-9]/', $string)) // '/[^a-z\d]/i'
        {
            return true;
        }else{
            return false;
        }
    }
    public function specialCharactersRemove($string)
    {
        $string = str_replace("&amp;", '&', $string);
        $string = str_replace("&#39;", "'", $string);
        $string = str_replace("&lt;", '<', $string);
        $string = str_replace("&gt;", '>', $string);
        $string = str_replace("&quot;", '"', $string);
        $string = str_replace(':', '', $string);
        $string = str_replace(',', '', $string);
        $string = str_replace('|', '', $string);
        $string = str_replace('(', '', $string);
        $string = str_replace(')', '', $string);
        $string = str_replace('}', '', $string);
        $string = str_replace('{', '', $string);
        $string = str_replace(']', '', $string);
        $string = str_replace('[', '', $string);


        return $string;
    }
    public function isReferalActive()
    {
        try{
            $referal_code_is_active = DB::table('referal_settings')
                ->first();
            if($referal_code_is_active->is_active)
            {
                return 1;
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            Log::error("Error on Extras.php->isReferalActive() : " . json_encode($e));
            return 0;
        }
    }
}
