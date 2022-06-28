<?php

namespace App\Mail\Facilitator\Notification;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class FacilitatorResponseSubmittedNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $student=Student::find( $this->student_id);
        $course=Course::find($this->course_id);

        return $this->markdown('emails.facilitator.notification.facilitator_response_submitted_notification')->with([

            'course_id'=>$this->course_id,
            'course_title'=>$course->title,
            'course_thumbnail'=>$course->thumbnail,
            'student_profile_pic'=>$student->profile_picture,
            'student_name'=>$student->name,
        ]);
    }
}
