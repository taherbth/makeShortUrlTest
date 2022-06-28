<?php

namespace App\Notifications\Student;

use App\Mail\Student\Notification\StudentNewCourseUploadNotificationMail;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentNewCourseUploadNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $course_id;
    public function __construct($course_id)
    {
        $this->course_id=$course_id;

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
     * @return StudentNewCourseUploadNotificationMail
     */
    public function toMail($notifiable)
    {
        return (new StudentNewCourseUploadNotificationMail($this->course_id))
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
        return [
            'notification_type'=>3,
            'course_id'=>$this->course_id,
            'course_thumbnail'=>$course->thumbnail,
            'course_title'=>$course->title
        ];
    }
}
