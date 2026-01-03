<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Models\Notification;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $notifications = Notification::where([
            'user_id' => Auth::id(),
            'deleted_at' => null
        ])
            ->select('id', 'event', 'event_type', 'notifiable_id', 'notifiable_type', 'read_at')
            ->with([
                'notifiable' => function ($morphTo) {
                    $morphTo->morphWith([
                        Invitation::class => ['project', 'inviter', 'invitee'],
                        Task::class => ['project'],
                    ]);
                }
            ])
            ->get();

        return response()->json([
            'success' => true,
            'notifications' => $notifications
        ]);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse {
        try {
            Notification::where('user_id', Auth::id())->findOrFail($id)->update([
                'deleted_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Уведомление успешно удалено.'
            ]);
        } catch (ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
