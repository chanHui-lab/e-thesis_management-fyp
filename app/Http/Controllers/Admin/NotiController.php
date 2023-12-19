<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class NotiController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->id());
        // Mark unread notifications as read
        // $user->unreadNotifications->markAsRead();
        // dd($user->id);

        $notifications = DB::table('notifications')
            ->where('notifiable_id', auth()->id())
            ->where('notifiable_type', get_class(auth()->user()))
            ->get();

        // dd($notifications);
        // dd($user->notifications); // Check if notifications are loaded

        // Load only unread notifications for the user
        // $user->load('unreadNotifications');
        $user->load('notifications');

        // Debugging: Dump and die to inspect unread notifications
        // dd($user->unreadNotifications);
        // dd($user->notifications);

        return view('admin.admindashinside.notificationindex', ['notifications' => $user->notifications]);
    }

    public function indexStu()
    {
        $user = User::find(auth()->id());
        // Mark unread notifications as read
        // $user->unreadNotifications->markAsRead();
        // dd($user->id);
        // dd($user);

        // $notifications = DB::table('notifications')
        //     ->where('notifiable_id', auth()->id())
        //     ->where('notifiable_type', get_class(auth()->user()))
        //     ->get();
        // dd($notifications);

        $notifications = DB::table('notifications')
            ->where('notifiable_type', User::class) // Assuming User is the model for students
            ->get();

        // dd($notifications);
        // $user->unreadNotifications->markAsRead();

        // Load only unread notifications for the user
        $user->load('unreadNotifications');

        // $user->load('notifications');

        // dd( $user-> notifications);
        // Debugging: Dump and die to inspect unread notifications
        // dd($user->unreadNotifications);
        // dd($user->notifications);
        return response()->json(['notifications' => $user->notifications])->header('Content-Type', 'application/json');

        // return view('student.studentinside.notificationindex', ['notifications' => $user->notifications]);
    }

    public function markNotificationAsRead($notificationId)
    {
        // Find the notification
        $notification = Auth::user()->notifications()->find($notificationId);

        // Mark the notification as read
        if ($notification) {
            $notification->markAsRead();
        }

        // Return a response, you can customize this based on your needs
        return Response::json(['status' => 'success']);
    }


}
