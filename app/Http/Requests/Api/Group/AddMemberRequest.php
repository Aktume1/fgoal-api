<?php

namespace App\Http\Requests\Api\Group;

use App\Http\Requests\Api\AbstractRequest;
use Illuminate\Foundation\Http\FormRequest;

class AddMemberRequest extends AbstractRequest
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
            'email' => 'required|email',
            'role' => 'required|integer',
        ];
    }
}
