<?php

namespace App\Mail\Student\Notification;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentAllChaptersOfTopicWatchedNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $course=Course::find($this->course_id);

        return $this->markdown('emails.student.notification.student_all_chapters_of_topic_watched_notification_mail')->with([

            'course_id'=>$this->course_id,
            'course_title'=>$course->title,
            'course_thumbnail'=>$course->thumbnail,
            'course_no_of_chapter'=>$this->chapter_count,
            'course_no_of_episode'=>$this->episode_count,
        ]);
    }
}
