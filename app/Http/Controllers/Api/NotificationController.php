<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{

    /**
     * Return all notification
     *
     * @return App\Http\Resources\NotificationResource
     */
    public function index(Request $request)
    {
        return NotificationResource::collection(Notification::for(auth()->user())->get());
    }

    /**
     * Delete specific notification
     *
     * @param  App\Models\Notification $notification
     * @return Response
     */
    public function destroy(Notification $notification)
    {
        $notification = auth()->user()->notifications()->find($notification->id);

        if ($notification) {
            $notification->delete();
        }

        return response()->json([], 200);
    }

    /**
     * Mark specific notification as read
     *
     * @param  App\Models\Notification $notification
     * @return Response
     */
    public function read(Notification $notification)
    {
        $notification = auth()->user()->notifications()->find($notification->id);

        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json([], 200);
    }

    /**
     * Mark specific notification as unread
     *
     * @param  App\Models\Notification $notification
     * @return Response
     */
    public function unread(Notification $notification)
    {
        $notification = auth()->user()->notifications()->find($notification->id);

        if ($notification) {
            $notification->markAsUnread();
        }

        return response()->json([], 200);
    }

    /**
     * Mark all notification as read
     *
     * @return Response
     */
    public function markAllRead()
    {
        $notification = Notification::for(auth()->user())->get();

        if ($notification) {
            $notification->each->markAsRead();
        }

        return response()->json([], 200);
    }

    /**
     * Return unread notifications count
     *
     * @return Response
     */
    public function unreadNotifCount()
    {
        return response()->json([
            'count' => Notification::for(auth()->user())->get()->count()
        ]);
    }
}
