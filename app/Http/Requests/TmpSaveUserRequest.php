<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TmpSaveUserRequest extends FormRequest
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
            'email' => 'required|email|bail|unique:users|max:30',
            'password' => 'required|min:8|max:25',
            'confirm_password' => 'required|same:password'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен.',
            'email.email' => 'Укажите корректный email.',
            'email.unique' => 'Пользователь с таким email уже существует.',
            'email.max' => 'Максимальная длина 30 символов.',
            'password.required' => 'Пароль обязателен',
            'password.min' => 'Минимальная длина 8 символов',
            'password.max' => 'Максимальная длина 25 символов',
            'confirm_password.required' => 'Подтвердите пароль',
            'confirm_password.same' => 'Пароли не совпадают'
        ];
    }
}
