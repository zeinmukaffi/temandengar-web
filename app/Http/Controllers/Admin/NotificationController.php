<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display all counselor notifications.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->paginate(15);

        $unreadCount = $user->unreadNotifications()->count();

        return view('admin.notifications.index', compact(
            'notifications',
            'unreadCount'
        ));
    }


    /**
     * Mark one notification as read.
     */
    public function markAsRead(
        Request $request,
        string $notification
    ): RedirectResponse {

        $record = $request->user()
            ->notifications()
            ->whereKey($notification)
            ->firstOrFail();

        $record->markAsRead();

        // Only redirect to internal dashboard routes.
        $destination = $record->data['url'] ?? null;

        if (
            !is_string($destination) ||
            !str_starts_with($destination, '/dashboard/')
        ) {
            $destination = route('admin.notifications.index');
        }

        return redirect($destination);
    }


    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(
        Request $request
    ): RedirectResponse {

        $request->user()
            ->unreadNotifications()
            ->update([
                'read_at' => now(),
            ]);

        return back()->with(
            'success',
            'Semua notifikasi berhasil ditandai sebagai dibaca.'
        );
    }
}