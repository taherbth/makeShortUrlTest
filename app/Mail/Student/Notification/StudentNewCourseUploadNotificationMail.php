<?php

namespace App\Mail\Student\Notification;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentNewCourseUploadNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $course_id;
    public function __construct($course_id)
    {
        $this->course_id=$course_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $course=Course::find($this->course_id);

        return $this->markdown('emails.student.notification.student_new_course_upload_notification_mail')->with([

            'course_id'=>$this->course_id,
            'course_title'=>$course->title,
            'course_thumbnail'=>$course->thumbnail,
        ]);
    }
}
