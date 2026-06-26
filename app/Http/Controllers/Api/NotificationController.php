<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()
            ->notifications()
            ->paginate(10);
    }

    public function seen(DatabaseNotification $notification)
    {
        $this->authorize('update', $notification);
        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
        ]);
    }
}
