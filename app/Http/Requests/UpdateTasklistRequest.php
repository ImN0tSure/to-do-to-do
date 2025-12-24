<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTasklistRequest extends FormRequest
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
        $tasklist_id = $this->route('tasklist');

        return [
            'name' => 'required|max:255|min:3',
            'oldName' => [
                'required',
                'max:255',
                'min:3',
                Rule::exists('tasklists', 'name')
                    ->where('id', $tasklist_id)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле с названием обязательно.',
            'name.max' => 'Максимальная длина 255 символов.',
            'name.min' => 'Минимальная длина 3 символа.',
            'oldName.required' => 'Поле со старым названием обязательно.',
            'oldName.max' => 'Максимальная длина 255 символов.',
            'oldName.min' => 'Минимальная длина 3 символа.',
        ];
    }
}
