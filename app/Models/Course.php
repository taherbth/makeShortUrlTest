<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Course extends Model
{
    use HasFactory,SearchableTrait;

    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'courses.title' => 10,
            'courses.details' => 9,
            'courses.topic' => 6,
            'courses.chapter' => 5,
            'courses.episode' => 2,
        ],
    ];


    protected $fillable = [
        'video_original_name','question','path','facilitator_id',
        'title', 'details', 'video_original_name', 'path', 'thumbnail', 'topic', 
                'chapter', 'episode', 'is_public', 'is_draft', 'publishable_id', 'durationInSec' , 'upload_status', 'publishable_type'
    ];
    public function course_question_c(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(courseQuestion::class);
    }
    public function course_question_option_c(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(courseQuestionOption::class,courseQuestion::class,'course_id','course_question_id');
    }
    public function student_response_c(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(studentResponses::class,courseQuestion::class);
    }
//    public function facillitor_c(){
//        return $this->belongsTo(Facilitator::class);
//    }
    public function publishable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function student_c(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Student::class,'course_student');
    }
    public function watchHistory_c(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(watchHistory::class);
    }
}
