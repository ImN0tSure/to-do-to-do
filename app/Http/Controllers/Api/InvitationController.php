<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateInvitationRequest;
use App\Http\Requests\ResponseInvitationRequest;
use App\Models\Invitation;
use App\Models\Notification;
use App\Models\ProjectParticipant;
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

    public function accept(ResponseInvitationRequest $request): \Illuminate\Http\JsonResponse
    {
        $validate_data = $request->validated();

        $notifiable_id = $validate_data['notifiable_id'];

        $this->updateInvitation($notifiable_id, true);

        $project_id = Invitation::where('id', $notifiable_id)->first()->project_id;

        ProjectParticipant::create([
            'project_id' => $project_id,
            'user_id' => Auth::id(),
            'status' => '2'
        ]);

        $this->deleteOriginalInvitationNotification($notifiable_id);

        return response()->json([
            'success' => true,
            'message' => 'Приглашение принято.'
        ]);
    }

    public function decline(ResponseInvitationRequest $request): \Illuminate\Http\JsonResponse
    {
        $validate_data = $request->validated();

        $notifiable_id = $validate_data['notifiable_id'];

        $this->updateInvitation($notifiable_id, false);

        $this->deleteOriginalInvitationNotification($notifiable_id);

        return response()->json([
            'success' => true,
            'message' => 'Приглашение отклонено.'
        ]);
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
