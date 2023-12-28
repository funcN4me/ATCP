<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class getAppTopCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date_format:Y-m-d'
        ];
    }

    public function attributes()
    {
        return [
            'date' => 'Дата',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Поле :attribute обязательно для заполнения',
            'date_format' => 'Формат поля :attribute должен быть в формате Год-месяц-день',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $headers = ['Content-Type' => 'application/json; charset=utf-8'];

        throw new HttpResponseException(response()->json(
            [
                'errors' => $validator->errors()->first()
            ],
            422,
            $headers,
            JSON_UNESCAPED_UNICODE)
        );
    }
}
