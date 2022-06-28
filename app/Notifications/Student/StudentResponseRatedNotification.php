<?php

namespace App\Notifications\Student;

use App\Mail\Student\Notification\StudentResponseRatedNotificationMail;
use App\Models\Course;
use App\Models\Facilitator;
use App\Models\Institution;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentResponseRatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $publisher_id;
    public $course_id;
    public $publisher_type;
    public function __construct($publisher_id,$course_id,$publisher_type)
    {
        $this->publisher_id=$publisher_id;
        $this->course_id = $course_id;
        $this->publisher_type = $publisher_type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if($notifiable->is_mailable == 1)
            return ['mail','database'];
        else
            return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return StudentResponseRatedNotificationMail
     */
    public function toMail($notifiable)
    {
        return (new StudentResponseRatedNotificationMail($this->publisher_id, $this->course_id))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $course=Course::find($this->course_id);
        if($this->publisher_type == 1)
            $publisher=Facilitator::find($this->publisher_id);

        else if($this->publisher_type == 3)
            $publisher=Institution::find($this->publisher_id);


        return [
            'notification_type'=>2,
            'course_id'=>$this->course_id,
            'publisher_name'=>$publisher->name,
            'publisher_dp'=>$publisher->profile_picture,
            'course_title'=>$course->title,
            'course_thumbnail'=>$course->thumbnail,
        ];
    }
}
