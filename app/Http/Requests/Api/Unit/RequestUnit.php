<?php

namespace App\Http\Requests\Api\Unit;

use App\Http\Requests\Api\AbstractRequest;

class RequestUnit extends AbstractRequest
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
            'unit' => 'required|unique:units',
        ];
    }
}
