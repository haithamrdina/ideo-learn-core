<?php

namespace IdeoLearn\Core\Helpers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class BaseRequestHelper
{
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    public static function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->toArray();

        // If there's only one error, we'll send it as a string
        if (count($errors) === 1) {
            $errors = array_values($errors)[0][0];
        } else {
            // Format errors as [field => message] array
            $errors = array_map(function ($field, $messages) {
                return ['field' => $field, 'message' => $messages[0]];
            }, array_keys($errors), $errors);
        }

        throw new HttpResponseException(
            ApiResponseHelper::error("Bad Request", $errors, Response::HTTP_BAD_REQUEST)
        );
    }
}
