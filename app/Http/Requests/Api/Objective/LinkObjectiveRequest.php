<?php

namespace App\Http\Requests\Api\Objective;

use App\Http\Requests\Api\AbstractRequest;

class LinkObjectiveRequest extends AbstractRequest
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
            'objective_id' => 'required|integer|min:1',
            'key_result_id' => 'required|string|min:1',
        ];
    }
}
