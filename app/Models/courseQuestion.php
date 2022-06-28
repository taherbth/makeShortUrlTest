<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courseQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'question','question_type','answer_length','answer_placeholder','answer_min_length','answer_max_length',
    ];
    public function student_response_cq(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(studentResponses::class,'course_question_id');
    }
    public function course_cq(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
    public function course_question_option_cq(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(courseQuestionOption::class);
    }
}
