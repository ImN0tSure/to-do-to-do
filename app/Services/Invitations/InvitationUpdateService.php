<?php

namespace App\Services\Invitations;

use App\Models\Invitation;
use App\Models\ProjectParticipant;
use App\Services\ProjectParticipant\CreateProjectParticipantService;
use Illuminate\Support\Facades\Auth;

class InvitationUpdateService
{
    public function execute($notifiable_id, $is_accepted)
    {
        $response = 'отклонил';

        if($is_accepted){
            $this->accepted($notifiable_id);
            $response = 'принял';
        }

        $invitation = Invitation::find($notifiable_id);
        $invitation->is_accepted = $is_accepted;
        $invitation->save();

        return $response;
    }

    private function accepted($notifiable_id)
    {
        $project_id = Invitation::where('id', $notifiable_id)->first()->project_id;
        $participant_service = new CreateProjectParticipantService();

        $participant_service->execute(
            $project_id,
            Auth::id(),
            '2',
            'executor'
        );
    }

}
