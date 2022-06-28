<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courseQuestionOption extends Model
{
    protected $fillable = [
        'courseQuestion_option', 'is_answer',
    ];
    use HasFactory;
    public function course_question_option_answer_cqo(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(courseQuestionOptionAnswer::class);
    }

    public function course_question_cqo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(courseQuestion::class);
    }
}
