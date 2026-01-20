<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInvitationRequest;
use App\Http\Requests\ResponseInvitationRequest;
use App\Models\Invitation;
use App\Models\Notification;
use App\Models\ProjectParticipant;
use App\Services\GetProjectId;
use App\Services\Invitations\InvitationCreateService;
use App\Services\Invitations\InvitationUpdateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InvitationController extends Controller
{
    public function create(
        CreateInvitationRequest $request,
        InvitationCreateService $invitation_service
    ): \Illuminate\Http\JsonResponse {
        $project_id = GetProjectId::byUrl($request->project_url);
        Gate::authorize('project.participant.invite', $project_id);

        $invitation_service->execute($request->email, $project_id);

        return response()->json([
            'success' => true,
            'message' => 'Уведомление с приглашением отправлено пользователю.'
        ]);
    }

    public function update(
        ResponseInvitationRequest $request,
        InvitationUpdateService $invitation_service
    ): \Illuminate\Http\JsonResponse {

        $notifiable_id = $request->notifiable_id;
        $is_accepted = (bool) $request->is_accepted;

        $response = $invitation_service->execute($notifiable_id, $is_accepted);

        $this->deleteOriginalInvitationNotification($notifiable_id);

        return response()->json([
            'success' => true,
            'message' => 'Приглашение ' . $response
        ]);
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
