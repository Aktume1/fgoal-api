<?php

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\AbstractRequest;

class RequestUser extends AbstractRequest
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
            'email' => 'required|email|max:255',
            'name' => 'required',
            'code' => 'required',
            'birthday' => 'required|date_format:Y-m-d',
            'gender' => 'required',
            'phone' => 'required|max:12|min:9',
            'mission' => 'required',
            'avatar' => 'mimes:jpeg,jpg,png|max:1000',
        ];
    }
}
