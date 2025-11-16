<?php

namespace App\Http\Requests;

use App\Services\GetProjectId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
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
        $project_url = $this->route('project');
        $project_id = GetProjectId::byUrl($project_url);


        return [
            'name' => 'required|max:255|min:3',
            'description' => 'required|max:1500',
            'executor_id' => [
                'nullable',
                'integer',
                Rule::exists('project_participants', 'user_id')
                    ->where(function ($query) use ($project_id) {
                        $query->where('project_id', $project_id);
                    })
            ],
            'tasklist_id' => [
                'required',
                'integer',
                Rule::exists('tasklists', 'id')
                    ->where(function ($query) use ($project_id) {
                        $query->where('project_id', $project_id);
                    }),
            ],
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i',
            'priority' => 'integer|required|min:1|max:3'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно.',
            'name.max' => 'Максимальная длина 200 символов.',
            'name.min' => 'Минимальная длина 3 символа.',
            'description.required' => 'Поле обязательно для заполнения.',
            'description.max' => 'Максимальная длина 1500 символов.',
            'executor_id.exists' => 'Пользователь не в проекте.',
            'tasklist_id.required' => 'Выберите список для размещения задачи.',
            'tasklist_id.exists' => 'Такого списка задач не существует.',
            'end_date.required' => 'Поле обязательно для заполнения.',
            'end_date.date' => 'Поле должно быть корректной датой.',
            'end_time.required' => 'Поле обязательно для заполнения.',
            'end_time.date_format' => 'Поле должно быть временем в формате H:i',
            'priority' => 'Выберите приоритет из списка.'
        ];
    }
}
