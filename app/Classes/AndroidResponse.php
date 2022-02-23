<?php
namespace App\Classes;
use \stdClass;

class AndroidResponse
{
    public function getStatus($status_code ,$message) 
    {
        $status = new stdClass();
        $status->status = $status_code;
        $status->message = $message;
        return $status;
    }
}