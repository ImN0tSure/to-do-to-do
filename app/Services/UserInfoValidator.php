<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class UserInfoValidator
{
    public static function check($data)
    {
        return Validator::make($data, [
            'avatar_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048|bail',
            'surname' => 'required|string|max:20|bail',
            'name' => 'required|string|max:20|bail',
            'patronymic' => 'nullable|string|max:20|bail',
            'phone' => 'nullable|string|max:20|bail',
            'contact_email' => 'required|email|max:30|bail',
            'about' => 'nullable|string|max:1500|bail',
        ], [
            'avatar_img.mimes' => 'Поддерживаются следующие форматы: jpeg, png, jpg, gif, svg',
            'avatar_img.max' => 'Максимальный размер 2048 байт',
            'avatar_img.image' => 'Вставьте картинку',
            'surname.required' => 'Это поле обзяательно для заполнения',
            'surname.max' => 'Максимальная длина 20 символов',
            'name.required' => 'Это поле обзяательно для заполнения',
            'name.max' => 'Максимальная длина 20 символов',
            'patronymic.max' => 'Максимальная длина 20 символов',
            'phone.max' => 'Максимальная длина 20 символов',
            'contact_email.required' => 'Email для связи обязателен',
            'contact_email.email' => 'Укажите корректный email',
            'contact_email.max' => 'Максимальная длина 30 символов',
            'about.max' => 'Максимальная длина 1500 символов',
        ]);
    }
}
