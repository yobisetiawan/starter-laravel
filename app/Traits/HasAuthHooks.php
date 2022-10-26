<?php

namespace App\Traits;

use App\Events\UserResetPasswordEvent;

trait HasAuthHooks
{

    public function __beforeLogin()
    {
        //empty code
    }

    public function __afterLogin()
    {
        //empty code
    }


    public function __beforeRegister()
    {
        //empty code
    }

    public function __afterRegister()
    {
        //empty code
    }

    public function __beforeForgotPassword()
    {
        //empty code
    }

    public function __afterForgotPassword()
    {
        //empty code
    }

    public function __beforeVerifyResetPassword()
    {
        //empty code
    }

    public function __afterVerifyResetPassword()
    {
        //empty code
    }


    public function __beforeResetPassword()
    {
        //empty code
    }

    public function __beforeVerifyEmail()
    {
        //empty code
    }

    public function __afterResetPassword()
    {
        //empty code
    }

    public function __beforeLogout()
    {
        //empty code
    }

    public function __afterLogout()
    {
        //empty code
    }

    public function __afterVerifyEmail()
    {
        //empty code
    }
}
