<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInvitationRequest;
use App\Models\Invitation;
use App\Models\User;
use App\Services\GetProjectId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function create(CreateInvitationRequest $request): \Illuminate\Http\JsonResponse
    {
        $invitee_id = User::where('email', $request->email)->first()->id;
        $project_id = GetProjectId::byUrl($request->project_url);

        Invitation::create([
            'inviter_id' => Auth::id(),
            'invitee_id' => $invitee_id,
            'project_id' => (integer)$project_id,
        ]);

        return response()->json([
            'success' =>true,
            'message' => 'Уведомление с приглашением отправлено пользователю.'
        ]);
    }
}
