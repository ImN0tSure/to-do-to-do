<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Notification;
use App\Models\ProjectParticipant;
use App\Models\User;
use App\Services\CheckParticipant;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InvitationController extends Controller
{
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = $this->validateIncomingData($request->all(), 'create');

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

    public function accept(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = $this->validateIncomingData($request->all());

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors(),
                'status' => 'error'
            ]);
        }

        $notifiable_id = ($validation->validated())['notifiable_id'];

        $this->updateInvitation($notifiable_id, true);

        $project_id = Invitation::where('id', $notifiable_id)->first()->project_id;

        ProjectParticipant::create([
            'project_id' => $project_id,
            'user_id' => Auth::id(),
            'status' => '2'
        ]);

        $this->deleteOriginalInvitationNotification($notifiable_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Приглашение принято.'
        ]);
    }

    public function decline(Request $request): \Illuminate\Http\JsonResponse
    {
        $validation = $this->validateIncomingData($request->all());

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors(),
                'status' => 'error'
            ]);
        }

        $notifiable_id = ($validation->validated())['notifiable_id'];

        $this->updateInvitation($notifiable_id, false);

        $this->deleteOriginalInvitationNotification($notifiable_id);

        return response()->json([
            'status' => 'success',
            'message' => 'Приглашение отклонено.'
        ]);
    }

    protected function validateIncomingData($data, $case = 'accept_or_decline')
    {
        return match ($case) {
            'create' => Validator::make($data, [
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
            ]),
            'accept_or_decline' => Validator::make($data, [
                'notifiable_id' => [
                    'required',
                    'integer',
                    Rule::exists('invitations', 'id')->where(function ($query) {
                        $query->where('invitee_id', Auth::id());
                    })
                ],
            ], [
                'notifiable_id' => 'id обязателен и должен быть числом',
                'notifiable_id.exists' => 'Нет пользователя с таким приглашением'
            ]),
        };
    }

    protected function updateInvitation($notifiable_id, bool $is_accepted): void
    {
        // Используем сохранение через модель, чтобы отработал InvitationObserver.
        // Кто же знал, что Invitation::where() это прямое обращение к БД, а оно не триггерит observer updated?
        $invitation = Invitation::find($notifiable_id);
        $invitation->is_accepted = $is_accepted;
        $invitation->save();
    }

    protected function deleteOriginalInvitationNotification($notifiable_id): void
    {
        Notification::where([
            'notifiable_id' => $notifiable_id,
            'event_type' => 'created'
        ])->update([
            'deleted_at' => now()
        ]);
    }
}
