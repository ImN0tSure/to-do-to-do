<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|same:new_password'
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Пароль обязателен.',
            'password.min' => 'Минимальная длина пароля 8 символов.',
            'new_password.required' => 'Введите новый пароль.',
            'new_password.min' => 'Минимальная длина нового пароля 8 символов.',
            'confirm_new_password.required' => 'Подтвердите новый пароль.',
            'confirm_new_password.same' => 'Пароли не совпадают.'
        ];
    }
}
