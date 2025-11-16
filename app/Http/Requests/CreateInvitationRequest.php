<?php

namespace App\Http\Requests;

use App\Models\Invitation;
use App\Models\User;
use App\Services\CheckParticipant;
use App\Services\GetProjectId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CreateInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $project_id = GetProjectId::byUrl($this->input('project_url'));

        return Gate::allows('create', [Invitation::class, $project_id]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:30|exists:users,email|bail',
            'project_url' => 'required|string|min:10|max:10|exists:projects,url|bail'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен',
            'email.max' => 'Максимальная длина email должна быть меньше 30 символов',
            'email.exists' => 'Пользователь с таким email не найден',
            'project_url.required' => 'Url проекта обязателен',
            'project_url.string' => 'Url проекта должен быть строкой',
            'project_url.min' => 'Url проекта состоит из 10 символов',
            'project_url.max' => 'Url проекта состоит из 10 символов',
            'project_url.exists' => 'Такого проекта не существует'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');
            $project_url = $this->input('project_url');

            $invitee_id = User::where('email', $email)->first()->id;
            $project_id = GetProjectId::byUrl($project_url);

            if (!checkParticipant::project($this->project_url, Auth::id())) {
                $validator->errors()->add('project_url', 'Вы не состоите в проекте');
            } elseif (checkParticipant::project($this->project_url, $invitee_id)) {
                $validator->errors()->add('project_url', 'Пользователь уже состоит в проекте');
            } elseif (Invitation::where([
                'project_id' => $project_id,
                'invitee_id' => $invitee_id,
                'is_accepted' => null
            ])->exists()) {
                $validator->errors()->add(
                    'project_url',
                    'Пользователь уже получил приглашения, но ещё ответил на него'
                );
            }
        });
    }
}
