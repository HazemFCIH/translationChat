<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:250',
            'email' => 'required|email|unique:users,email|max:250',
            'mobile_number' => 'required|string|unique:users,mobile_number|max:50',
            'fcm_token' => 'required|string',
        ];
    }

}
