<?php

namespace App\Services;

use Nexmo\Laravel\Facade\Nexmo;

class NexmoService
{

    public function sendOTP($phone)
    {
        $verification = Nexmo::verify()->start([
            'number' => $phone,
            'brand'  => env('APP_NAME'),
            'workflow_id' => 2, // sms, sms, telp
            'sender_id' => env('NEXMO_FROM')
        ]);

        return $verification->getRequestId();
    }

    public function checkOTP(string $otp_id, string $code)
    {
        return Nexmo::verify()->check($otp_id, $code);
    }
}
