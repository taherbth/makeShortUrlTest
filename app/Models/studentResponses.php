<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studentResponses extends Model
{
    use HasFactory;
    protected $fillable=[
        'student_id','student_response','is_public','course_question_id','created_at',
    ];
    public function course_question_sr(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        //return $this->belongsToMany(courseQuestion::class,'course_questions','course_question_id','id');
        return $this->belongsTo(courseQuestion::class,'course_question_id');
    }
    public function student_sr(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }
    public function response_rating_sr(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        //return $this->hasOne(responseRating::class,'id','student_responses_id');
        return $this->hasOne(responseRating::class);
    }

}
