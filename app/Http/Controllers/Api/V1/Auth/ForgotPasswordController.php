<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Modules\BaseAuth;
use App\Http\Requests\Api\V1\Auth\ApiForgotPasswordRequest;
use App\Repositories\Auth\ForgotPasswordRepository;

class ForgotPasswordController extends BaseAuth
{
    public $validatorForgotPassword = ApiForgotPasswordRequest::class;


    public function __afterForgotPassword()
    {
        $repo = new ForgotPasswordRepository;
        $repo->sendNotifForgotPassword($this->user);
    }
}
