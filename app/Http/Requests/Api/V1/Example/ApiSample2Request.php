<?php

namespace App\Http\Requests\Api\V1\Example;

use Illuminate\Foundation\Http\FormRequest;

class ApiSample2Request extends FormRequest
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
            'title' => 'required|max:120',
            'description' => 'required|max:2000',
            'sample_id' => 'required|exists:samples,uuid',
            'date' => 'required|date_format:Y-m-d',
            'date_time' => 'required|date_format:Y-m-d H:i'
        ];
    }


}
