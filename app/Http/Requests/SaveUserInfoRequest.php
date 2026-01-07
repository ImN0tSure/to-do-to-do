<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveUserInfoRequest extends FormRequest
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
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|bail',
            'surname' => 'required|string|max:20|bail',
            'name' => 'required|string|max:20|bail',
            'patronymic' => 'nullable|string|max:20|bail',
            'phone' => 'nullable|string|max:20|bail',
            'contact_email' => 'required|email|max:30|bail',
            'about' => 'nullable|string|max:1500|bail'
        ];
    }

    public function messages(): array
    {
        return [
            'avatar_file.mimes' => 'Поддерживаются следующие форматы: jpeg, png, jpg, gif, svg',
            'avatar_file.max' => 'Максимальный размер 2048 байт',
            'avatar_file.image' => 'Вставьте картинку',
            'surname.required' => 'Фамилия обзяательна для заполнения',
            'surname.max' => 'Максимальная длина 20 символов',
            'name.required' => 'Имя обзяательно для заполнения',
            'name.max' => 'Максимальная длина 20 символов',
            'patronymic.max' => 'Максимальная длина 20 символов',
            'phone.max' => 'Максимальная длина 20 символов',
            'contact_email.required' => 'Email для связи обязателен',
            'contact_email.email' => 'Укажите корректный email',
            'contact_email.max' => 'Максимальная длина 30 символов',
            'about.max' => 'Максимальная длина 1500 символов'
        ];
    }
}
