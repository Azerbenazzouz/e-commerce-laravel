<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest {

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
                'status' => 422
            ], 422)
        );
    }

}