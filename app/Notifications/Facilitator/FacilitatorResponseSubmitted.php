<?php

namespace App\Notifications\Facilitator;

use App\Mail\Facilitator\Notification\FacilitatorNewCoursePublishedNotificationMail;
use App\Mail\Facilitator\Notification\FacilitatorResponseSubmittedNotificationMail;
use App\Models\Course;
use App\Models\Facilitator;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class FacilitatorResponseSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $course_id,$student_id;
    public function __construct($course_id,$student_id)
    {
        $this->course_id=$course_id;
        $this->student_id=$student_id;
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
     * @return FacilitatorResponseSubmittedNotificationMail
     */
    public function toMail($notifiable)
    {

        return (new FacilitatorResponseSubmittedNotificationMail($this->course_id,$this->student_id))
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
        $student=Student::find( $this->student_id);
        $course=Course::find($this->course_id);

        return [
            'notification_type'=>1,
            'course_id'=>$this->course_id,
            'course_title'=>$course->title,
            'course_thumbnail'=>$course->thumbnail,
            'student_profile_pic'=>$student->profile_picture,
            'student_name'=>$student->name,

        ];
    }
}
