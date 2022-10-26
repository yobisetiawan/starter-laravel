<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait HasAuthPrepareQuery
{

    public function __prepareGetUser()
    {
        $request = $this->requestData;
        $this->query->where('email', strtolower($request->input('email')));
        return $this->query;
    }

    public function __prepareQueryLogin()
    {
        return $this->__prepareGetUser();
    }

    public function __prepareDataRegister($data)
    {
        if (!empty($data['email'])) {
            $data['email'] = strtolower($data['email']);
        }
        $data['password'] = Hash::make($data['password']);
        return $data;
    }

    public function __prepareQueryForgotPassword()
    {
        return $this->__prepareGetUser();
    }

    public function __prepareQueryVerifyResetPassword()
    {
        return $this->__prepareGetUser();
    }

    public function __prepareQueryResetPassword()
    {
        return $this->__prepareGetUser();
    }

    public function __prepareQueryVerifyEmail()
    {
        return $this->__prepareGetUser();
    }

    public function __prepareDataResetPassword($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $data;
    }

    public function __prepareDataVerifyEmail()
    {
        return [];
    }
}
