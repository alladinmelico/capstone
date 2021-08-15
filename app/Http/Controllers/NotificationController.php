<?php

namespace App\Http\Controllers;

use App\Notifications\NotificationDeleted;
use App\Notifications\NotificationRead;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function show(DatabaseNotification $notification)
    {
        if (empty($notification->read_at)) {
            $notification->markAsRead();
            Auth::user()->notify(new NotificationRead($notification->id));
        }

        if (empty($notification->data['url'])) {
            return redirect()->back();
        }

        return redirect($notification->data['url']);
    }

    public function destroy(DatabaseNotification $notification)
    {
        $notification->delete();

        Auth::user()->notify(new NotificationDeleted($notification->id));
    }
}
