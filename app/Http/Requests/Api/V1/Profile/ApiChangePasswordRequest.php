<?php

namespace App\Http\Requests\Api\V1\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Old password didn\'t match');
                }
            }],
            'password' => 'required|min:6|confirmed'
        ];
    }


    public function withValidator($factory)
    {
        if (!$factory->fails()) {

            $factory->after(function ($factory) {

                if (request('old_password') ==  request('password')) {

                    $factory->errors()->add('password', "You can't use the same password as your current password");
                }
            });
        }

        return $factory;
    }
}
