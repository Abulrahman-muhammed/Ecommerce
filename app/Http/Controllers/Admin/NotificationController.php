<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    
class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    // mark all as read
    public function readAll()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    // mark one as read
    public function readOne(Request $request)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($request->id);

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }
    }
