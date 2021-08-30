<?php
namespace App\Helpers;
use App\Model\User;
use App\Notifications\AllNotification;
use Illuminate\Support\Facades\Notification;

class NotificationHelper{

    public static function notifyAdmin($details)
    {
        $admins = User::permission(['authorise', 'verify', 'add administrator'])->get();
        foreach ($admins as $admin) {
            Notification::route('mail', $admin->email)->notify(new AllNotification($details));
        }
        Notification::route('mail', env('SUPPORT_MAIL'))->notify(new AllNotification($details));
    }
}
