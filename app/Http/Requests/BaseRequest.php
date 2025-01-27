<?php

namespace App\Http\Requests;

use App\Http\Resources\ApiResource;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class BaseRequest extends FormRequest {

    public function failedValidation(Validator $validator){

        throw new HttpResponseException(
            ApiResource::error($validator->errors()->toArray(), 'Validation failed', Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

}