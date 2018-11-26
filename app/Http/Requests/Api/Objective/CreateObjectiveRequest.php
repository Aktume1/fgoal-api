<?php

namespace App\Http\Requests\Api\Objective;

use App\Http\Requests\Api\AbstractRequest;

class CreateObjectiveRequest extends AbstractRequest
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
            'name' => 'required|string',
            'description' => 'string|nullable',
            'unit_id' => 'required|integer|min:1',
            'quarter_id' => 'required|integer|min:1',
            'parent_id' => 'integer|nullable|min:1',
            'target' => 'integer|min:1',
        ];
    }
}
