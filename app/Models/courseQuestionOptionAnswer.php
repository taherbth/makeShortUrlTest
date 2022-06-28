<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class courseQuestionOptionAnswer extends Model
{
    use HasFactory;
    public function course_question_option_cqoa(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(courseQuestionOption::class);
    }
}
