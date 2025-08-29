<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use App\Services\CheckParticipant;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvitationController extends Controller
{
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email|max:30|exists:users,email|bail',
            'project_url' => 'required|string|min:10|max:10|exists:projects,url|bail',
        ], [
            'email.required' => 'Email обязателен',
            'email.max' => 'Максимальная длина email должна быть меньше 30 символов',
            'email.exists' => 'Пользователь с таким email не найден',
            'project_url.required' => 'Url проекта обязателен',
            'project_url.string' => 'Url проекта должен быть строкой',
            'project_url.min' => 'Url проекта состоит из 10 символов',
            'project_url.max' => 'Url проекта состоит из 10 символов',
            'project_url.exists' => 'Такого проекта не существует'
        ]);

        if (!$validation->errors()->any()) {
            $new_participant_id = User::where('email', $request->email)->first()->id;
            $project_id = GetProjectId::byUrl($request->project_url);

            $validation->after(function ($validation) use ($request, $new_participant_id, $project_id) {
                if (!checkParticipant::project($request->project_url, Auth::id())) {
                    $validation->errors()->add('project_url', 'Вы не состоите в проекте');
                } elseif (checkParticipant::project($request->project_url, $new_participant_id)) {
                    $validation->errors()->add('project_url', 'Пользователь уже состоит в проекте');
                } elseif (Invitation::where([
                    'project_id' => $project_id,
                    'invitee_id' => $new_participant_id,
                    'is_accepted' => null
                ])->exists()) {
                    $validation->errors()->add(
                        'project_url',
                        'Пользователь уже получил приглашения, но ещё ответил на него'
                    );
                }
            });
        }

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors(),
                'status' => 'error'
            ]);
        }

        Invitation::create([
            'inviter_id' => Auth::id(),
            'invitee_id' => $new_participant_id,
            'project_id' => (integer)$project_id,
        ]);

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function accept($notifiable_id): \Illuminate\Http\JsonResponse
    {
        if ($this->checkInvitation($notifiable_id)) {
            $this->updateInvitation($notifiable_id, true);
            return response()->json([
                'status' => 'success',
                'message' => 'Приглашение принято.'
            ]);
        }

        return response()->json($this->invitationNotFoundResponse());
    }

    public function decline($notifiable_id): \Illuminate\Http\JsonResponse
    {
        if ($this->checkInvitation($notifiable_id)) {
            $this->updateInvitation($notifiable_id, false);
            return response()->json([
                'status' => 'success',
                'message' => 'Приглашение отклонено.'
            ]);
        }

        return response()->json($this->invitationNotFoundResponse());
    }

    protected function checkInvitation($notifiable_id): bool
    {
        return (bool)Invitation::where([
            'id' => $notifiable_id,
            'invitee_id' => Auth::id(),
            'is_accepted' => null
        ]);
    }

    protected function updateInvitation($notifiable_id, bool $is_accepted): void
    {
        Invitation::where('id', $notifiable_id)->update(['is_accepted' => $is_accepted]);
    }

    protected function invitationNotFoundResponse(): array
    {
        return [
            'status' => 'error',
            'message' => 'Приглашение отсутствует.'
        ];
    }
}
