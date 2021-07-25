<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ShortUrlPostRequest extends FormRequest
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
            'original_url' => 'required|url',
            'shortened' => 'sometimes|alpha_num|unique:App\Models\ShortUrl,shortened',
            'expired_at' => 'sometimes|date_format:Y-m-d\TH:i:s.u\Z|after:now',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(
            response()->json([
                'status' => 422,
                'error' => true,
                'message' => 'Validation errors',
                'data' => $errors
            ], 422)
        );
    }
}
