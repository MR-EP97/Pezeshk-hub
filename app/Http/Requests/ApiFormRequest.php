<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use App\Traits\CustomResponseTraits;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ApiFormRequest extends FormRequest
{
    use CustomResponseTraits;

    protected function failedValidation(Validator $validator): JsonResponse
    {
        throw new HttpResponseException($this->errorResponse(['message' => $validator->getMessageBag()], code: HttpResponse::HTTP_BAD_REQUEST));
    }

    protected function failedAuthorization(): JsonResponse
    {
        throw new HttpResponseException($this->errorResponse(['message' => 'Unauthorized'], code: HttpResponse::HTTP_UNAUTHORIZED));
    }


}
