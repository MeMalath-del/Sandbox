<?php

namespace App\Http\Controllers;

use App\Models\CustomNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = CustomNotification::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(CustomNotification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'تم تحديث الإشعار');
    }

    public function markAllAsRead()
    {
        CustomNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'تم تحديث جميع الإشعارات');
    }

    public function destroy(CustomNotification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'تم حذف الإشعار');
    }

    public function destroyAll()
    {
        CustomNotification::where('user_id', auth()->id())->delete();

        return back()->with('success', 'تم حذف جميع الإشعارات');
    }

    public function settings()
    {
        $user = auth()->user();
        $settings = $user->notification_settings ?? [
            'email_orders' => true,
            'email_promotions' => true,
            'email_reviews' => true,
            'sms_orders' => false,
            'sms_promotions' => false,
            'push_enabled' => true,
        ];

        return view('notifications.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $settings = [
            'email_orders' => $request->boolean('email_orders'),
            'email_promotions' => $request->boolean('email_promotions'),
            'email_reviews' => $request->boolean('email_reviews'),
            'sms_orders' => $request->boolean('sms_orders'),
            'sms_promotions' => $request->boolean('sms_promotions'),
            'push_enabled' => $request->boolean('push_enabled'),
        ];

        auth()->user()->update(['notification_settings' => $settings]);

        return back()->with('success', 'تم حفظ الإعدادات');
    }
}
