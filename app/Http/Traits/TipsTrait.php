<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;
trait TipsTrait{

    //SMS Misr
    public function SMS($phone, $message)
    {
        $response = 
        Http::post("https://smsmisr.com/api/SMS?
        environment=
        &username=
        &password=
        &language=
        &sender=
        &mobile={$phone}
        &message={$message}
        &DelayUntil="
        ,[]);

        return $response;
    }

    public function OTP($phone, $message)
    {
        $response = 
        Http::post("https://smsmisr.com/api/OTP?
        environment=
        &username=
        &password=
        &language=
        &sender=
        &mobile={$phone}
        &template=
        &otp={$message}
        &DelayUntil="
        ,[]);

        return $response;
    }

}
