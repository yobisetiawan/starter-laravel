<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Modules\BaseAuth;
use App\Http\Requests\Api\V1\Auth\ApiRegisterRequest;
use Illuminate\Support\Facades\Hash;

class RegisterController extends BaseAuth
{

    public $validatorRegister = ApiRegisterRequest::class;

    public function __prepareDataRegister($data)
    {
        if (!empty($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }

        $data['password'] = Hash::make($data['password']);

        return $data;
    }
}
