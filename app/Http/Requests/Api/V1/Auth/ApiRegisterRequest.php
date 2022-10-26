<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Constants\RuleConst;
use App\Repositories\Validator\PhoneRepository;
use Illuminate\Foundation\Http\FormRequest;

class ApiRegisterRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $verify_phone_repo = new PhoneRepository();
        $phone = $verify_phone_repo->preparePhoneValidation($this->phone ?? null);

        if (!empty($phone)) {
            $this->merge(['phone' => $phone]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|unique:users,phone|' . RuleConst::BASE_PHONE,
            'email' => 'required|email:rfc,dns|unique:users,email|' . RuleConst::MAX_STRING,
            'name' => 'required|max:60|min:3',
            'password' => 'required|min:6|confirmed',
        ];
    }
}
