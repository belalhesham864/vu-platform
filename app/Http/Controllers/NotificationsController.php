<?php

namespace App\Http\Controllers;

use App\Http\Resources\Notifications\NotificationsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function allNotifications()
    {
        $allNotifications = Auth::user()
        ->notifications()
        ->latest()
        ->paginate(10);

        return apiResponse(200 , "All Notifications Is Here" , NotificationsResource::collection($allNotifications));
    }

    public function unReadNotifications()
    {
        $user = Auth::user();

        $unreadNotifications = $user->unreadNotifications()->paginate();

        if ($unreadNotifications->isEmpty()) {
            return apiResponse(200, 'No unread notifications found');
        }

        return apiResponse(200, 'Unread notifications retrieved successfully', new NotificationsResource($unreadNotifications));
    }

    public function markAsRead($notification)
    {
        // $user = Auth::user();

        // $notification = $user->unreadNotifications()->find($id);

        // if (!$notification) {
        //     return apiResponse(404, 'Notification not found');
        // }

        $notification->markAsRead();

        return apiResponse(200, 'Notification marked as read successfully', new NotificationsResource($notification));
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        $unreadNotifications = $user->unreadNotifications();

        if (!$unreadNotifications->exists()) {
            return apiResponse(200, 'No unread notifications found');
        }

        $unreadNotifications->update([
            'read_at' => now()
        ]);

        return apiResponse(200, 'All notifications marked as read successfully');
    }

    public function destroy($notification)
    {
        $notification->delete();

        return apiResponse(200 , 'Notification Deleted Successfully');
    }
}
