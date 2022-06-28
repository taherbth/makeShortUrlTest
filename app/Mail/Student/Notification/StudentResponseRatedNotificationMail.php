<?php

namespace App\Mail\Student\Notification;

use App\Models\Course;
use App\Models\Facilitator;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentResponseRatedNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $facilitator_id;
    public $course_id;
    public function __construct($facilitator_id,$course_id)
    {
        $this->facilitator_id=$facilitator_id;
        $this->course_id = $course_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $facilitator=Facilitator::find( $this->facilitator_id);
        $course=Course::find($this->course_id);

        return $this->markdown('emails.student.notification.student_response_rated_notification_mail')->with([

            'course_title'=>$course->title,
            'course_thumbnail'=>$course->thumbnail,
            'student_profile_pic'=>$facilitator->profile_picture,
            'student_name'=>$facilitator->name,
        ]);
    }
}
