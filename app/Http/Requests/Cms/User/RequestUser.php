<?php

namespace App\Http\Requests\Cms\User;

use Illuminate\Foundation\Http\FormRequest;

class RequestUser extends FormRequest
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
            'password' => 'required',
            'name' => 'required',
            'code' => 'required',
            'birthday' => 'required|date_format:Y-m-d',
            'gender' => 'required',
            'phone' => 'required|max:12|min:9',
            'mission' => 'required',
            'avatar' => 'mimes:jpeg,jpg,png|max:1000',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => __('validation.warningName'),
            'email.unique' => __('validation.uniqueEmail'),
            'password.required' => __('validation.warningPass'),
            'code' => __('validation.codeUser'),
            'birthday' => __('validation.birthdayUser'),
            'gender' => __('validation.genderUser'),
            'phone' => __('validation.phoneUser'),
            'mission' => __('validation.missionUser'),
            'avatar' => __('validation.avatarUser'),
        ];
    }
}
