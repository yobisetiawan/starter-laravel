<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Modules\BaseAuth;
use App\Http\Requests\Api\V1\Auth\ApiLoginRequest;

class LoginController extends BaseAuth
{
    public $validatorLogin = ApiLoginRequest::class;
}
