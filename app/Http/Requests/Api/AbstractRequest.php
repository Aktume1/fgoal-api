<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class AbstractRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $dataResponse = formatResponse(VALIDATOR_ERROR, $validator->errors()->all());

        throw new HttpResponseException(response()->json($dataResponse, VALIDATOR_ERROR));
    }
}
