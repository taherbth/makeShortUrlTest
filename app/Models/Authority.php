<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class Authority extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $fillable = [
        'name', 'email','profile_picture',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    public static function return_id(){
        try{
            if(Authority::where('email',Auth::guard('api-authority')->user()->email)->first() && Auth::guard('api-authority')->user()->token()->role==4)
                return Auth::guard('api-authority')->user()->id;
            else
                return response(['message'=>'Unauthenticated'],401);
        }catch(\Exception $e){
            return response(['message'=>'Unauthenticated'],401);
        }

    }
    public function course_i(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Course::class,'publishable');
    }
    public static function return_authority_uploaded_courses()
    {
        return Course::where([
            ['publishable_id', Auth::guard('api-authority')->user()->id],
            ['publishable_type', 'App\Models\Authority']
        ]);
    }
    public static function return_authority_all_courses(): array
    {
        $courses= Course::where([
            ['publishable_id', Auth::guard('api-authority')->user()->id],
            ['publishable_type', 'App\Models\Authority'],
            ['upload_status',1]
        ]);

        return [
            'courses'=>$courses,
            'courses_id'=>$courses->pluck('id'),
            'courses_topics'=>$courses->pluck('topic'),
        ];
    }
}
