<?php

namespace App\Http\Requests;

use App\Models\ProjectParticipant;
use App\Rules\ExcludeParticipantsRule;
use App\Services\GetProjectId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExcludeParticipantsRequest extends FormRequest
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
        $project_url = $this->route('project');
        $project_id = GetProjectId::byUrl($project_url);
        $participants = ProjectParticipant::where('project_id', $project_id)->get();
        $user_status_in_project = $participants->firstWhere('user_id', Auth::id())->status;
        $count_participants = $participants->count();

        return [
            'ids' => [
                'required',
                'array',
                'max:' . $count_participants,
            ],
            'ids.*' => [
                'numeric',
                new ExcludeParticipantsRule($participants, $user_status_in_project)
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'ids.required' => 'Пустой массив.',
            'ids.array' => 'ids должен быть массивом.',
            'ids.max' => 'В проекте нет столько пользователей.',
            'ids.*.numeric' => 'id должен быть числом.'
        ];
    }
}
