<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Notification;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where([
            'user_id' => Auth::id(),
            'deleted_at' => null
        ])
            ->with([
                'notifiable' => function ($morphTo) {
                    $morphTo->morphWith([
                        Invitation::class => ['project', 'inviter'],
                        Task::class => [],
                    ]);
                }
            ])
            ->get()
            ->groupBy('event');
//        dump($notifications);
//        die();
        return view('notification.index', $notifications);
    }
}
