<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Models\Authority;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthorityApiNotificationController extends Controller
{
    public function get_all_notifications(){
        $notifications= Authority::find(Authority::return_id())
            ->notifications
            ->where('created_at','>',Carbon::now()->subDays(7))
            ->sortByDesc('created_at');
        $notification_count=0;

        foreach ($notifications as $notification){
            if($notification->unread()){
                $notification->markAsRead();
                $notification->is_read=0;
                $notification_count++;
            }
            else
                $notification->is_read=1;

        }
        return response([
            'unread_notifications'=>$notification_count,
            'notifications'=>$notifications
        ]);
    }

    public function get_notifications_status(): bool
    {
        if(
            Authority::find(Authority::return_id())
                ->unreadNotifications
                ->where('created_at','>',Carbon::now()->subDays(7))
                ->count() > 0
        )
            return true;
        else
            return false;
    }

}