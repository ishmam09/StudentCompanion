<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        // We do not mark as read here anymore, we wait for completion
        return redirect($notification->data['url'] ?? route('dashboard'));
    }
}
