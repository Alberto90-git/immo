<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    public function getRecent()
    {
        $notifications = Auth::user()->notifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? '',
                    'message' => $notification->data['message'] ?? '',
                    'icon' => $notification->data['icon'] ?? 'bx-bell',
                    'color' => $notification->data['color'] ?? 'primary',
                    'type' => $notification->data['type'] ?? '',
                    'read' => !is_null($notification->read_at),
                    'created_at' => $notification->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'status' => true,
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(Request $request)
    {
        $notification = Auth::user()->notifications()->find($request->id);
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['status' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => true]);
    }
}
