<?php

namespace App\Services\Invitations;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class InvitationCreateService
{
    public function execute($email, $project_id): Invitation {

        $invitee_id = User::where('email', $email)->first()->id;

        return Invitation::create([
            'inviter_id' => Auth::id(),
            'invitee_id' => $invitee_id,
            'project_id' => (integer)$project_id,
        ]);
    }
}
