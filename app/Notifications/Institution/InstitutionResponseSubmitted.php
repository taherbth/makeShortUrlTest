<?php

namespace App\Notifications\Institution;

use App\Mail\Facilitator\Notification\FacilitatorResponseSubmittedNotificationMail;
use App\Mail\Institution\Notification\InstitutionResponseSubmittedNotificationMail;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstitutionResponseSubmitted extends Notification implements ShouldQueue
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
     * @return InstitutionResponseSubmittedNotificationMail
     */
    public function toMail($notifiable)
    {
        return (new InstitutionResponseSubmittedNotificationMail($this->course_id,$this->student_id))
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
            'notification_type'=>5,
            'course_id'=>$this->course_id,
            'course_title'=>$course->title,
            'course_thumbnail'=>$course->thumbnail,
            'student_profile_pic'=>$student->profile_picture,
            'student_name'=>$student->name,

        ];
    }
}
