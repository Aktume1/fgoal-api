<?php

namespace App\Http\Requests\Cms\Quarter;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuarterRequest extends FormRequest
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
            'name' => 'required|max:191',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
            'expried' => 'required|numeric|min:0|max:1',
        ];
    }
}
