<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentNotificationController extends Controller
{
    public function get_notifications_status(): bool
    {
        if(
            Student::find(Student::return_id())
                ->unreadNotifications
                ->where('created_at','>',Carbon::now()->subDays(7))
                ->count() > 0
        )
            return true;
        else
            return false;
    }

    public function get_all_notifications(){
        $notifications= Student::find(Student::return_id())
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
}
