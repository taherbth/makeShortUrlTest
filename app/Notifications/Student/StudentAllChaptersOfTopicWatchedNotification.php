<?php

namespace App\Notifications\Student;

use App\Mail\Student\Notification\StudentAllChaptersOfTopicWatchedNotificationMail;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class StudentAllChaptersOfTopicWatchedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $course_id,$chapter_count,$episode_count;
    public function __construct($course_id, $chapter_count, $episode_count)
    {
        $this->course_id=$course_id;
        $this->chapter_count=$chapter_count;
        $this->episode_count=$episode_count;
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
     * @return StudentAllChaptersOfTopicWatchedNotificationMail
     */
    public function toMail($notifiable)
    {
        return (new StudentAllChaptersOfTopicWatchedNotificationMail($this->course_id, $this->chapter_count, $this->episode_count))
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
            'notification_type'=>4,
            'course_thumbnail'=>$course->thumbnail,
            'course_title'=>$course->title,
            'course_no_of_chapter'=>$this->chapter_count,
            'course_no_of_episode'=>$this->episode_count,
        ];
    }
}
