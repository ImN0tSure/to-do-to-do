<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveProjectRequest extends FormRequest
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
     * return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'description' => 'required | max:255',
            'end_date' => 'max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя проекта обязательно.',
            'description.required' => 'Описание проекта обязательно.',
            'description.max' => 'Максимальная длина 255 символов.',
            'end_date.max' => 'Максимальная длина 255 символов.'
        ];
    }
}
