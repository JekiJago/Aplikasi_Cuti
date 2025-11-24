<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $user = auth()->user();

        $notification = $user->notifications()->findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return back();
    }

    public function markAllAsRead()
    {
        $user = auth()->user();

        $user->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back();
    }
}
