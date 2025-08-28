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
    public function create(Request $request)
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
            'inviter_id' => Auth::id(),
            'invitee_id' => $new_participant_id,
            'project_id' => (integer)$project_id,
        ]);
    }

    public function confirm($user_id, $notification_id)
    {
    }

    public function decline($user_id, $notification_id)
    {
    }
}
