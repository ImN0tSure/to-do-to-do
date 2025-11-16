<?php

namespace App\Http\Requests;

use App\Services\GetProjectId;
use App\Services\isStatusHigherThan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
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

        $isExecutor = isStatusHigherThan::executor($project_id);

        if ($isExecutor) {
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
                'priority' => 'integer|required|min:1|max:3',
                'in_progress' => 'boolean'
            ];
        } else {
            return [
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
                ]
            ];
        }
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Это поле обязательно для заполнения.',
            'name.max' => 'Максимальная длина 255 символов.',
            'name.min' => 'Минимальная длина 3 символа.',
            'description.required' => 'Это поле обязательно для заполнения.',
            'description.max' => 'Описание не должно превышать 1500 символов.',
            'executor_id.exists' => 'Такого пользователя нет в проекте.',
            'tasklist_id.required' => 'Это поле обязательно.',
            'tasklist_id.integer' => 'Выберите список из предложенных.',
            'tasklist_id.exists' => 'Список задач отсутствует.',
            'end_date.required' => 'Это поле обязательно.',
            'end_date.date' => 'Это поле должно быть корректной датой.',
            'end_time.required' => 'Это поле обязательно.',
            'end_time.date_format' => 'Время должно быть в формате H:i',
            'priority.integer' => 'Выберите приоритет из списка.',
            'priority.required' => 'Это поле обязательно.',
            'priority.min' => 'Выберите приоритет из списка.',
            'priority.max' => 'Выберите приоритет из списка.',
            'in_progress.boolean' => 'Выберите статус из списка.'
        ];
    }
}
