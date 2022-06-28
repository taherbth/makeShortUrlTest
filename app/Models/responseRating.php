<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class responseRating extends Model
{
    protected $fillable = [
        'student_responses_id','response_rating',
    ];
    use HasFactory;
    public function facillator_rr(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Facilitator::class);
    }
    public function student_response_rr(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(studentResponses::class,'student_responses_id','id');
    }
    public function rateable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
