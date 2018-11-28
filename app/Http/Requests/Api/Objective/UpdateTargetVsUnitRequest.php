<?php

namespace App\Http\Requests\Api\Objective;

use App\Http\Requests\Api\AbstractRequest;

class UpdateTargetVsUnitRequest extends AbstractRequest
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
            'target' => 'required|integer|min:1',
            'unit_id' => 'required|integer|min:1',
        ];
    }
}
