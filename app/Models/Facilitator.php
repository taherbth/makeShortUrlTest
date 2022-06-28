<?php

namespace App\Models;

use App\Notifications\Facilitator\Auth\ResetPassword;
use App\Notifications\Facilitator\Auth\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Nicolaslopezj\Searchable\SearchableTrait;

class Facilitator extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens,SearchableTrait;

    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'facilitators.name' => 10,
            'facilitators.email' => 7,
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','institution_id','institution_url','profile_picture','email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public $facilitator_image='App\Models\Facilitator';

    public static function return_id(){
        try{
            if(Facilitator::where('email',Auth::guard('api-facilitator')->user()->email)->first() && Auth::guard('api-facilitator')->user()->token()->role==1)
                return Auth::guard('api-facilitator')->user()->id;
            else
                return response(['message'=>'Unauthenticated'],401);
        }catch(\Exception $e){
            return response(['message'=>'Unauthenticated'],401);
        }

    }

    public static function return_facilitator_uploaded_courses()
    {
        return Course::where([
            ['publishable_id', Facilitator::return_id()],
            ['publishable_type', 'App\Models\Facilitator'],
            ['upload_status',1]
        ]);
    }
    public static function return_other_facilitators(): \Illuminate\Support\Collection
    {
        return Facilitator::where([
            ['institution_id',Facilitator::find(Facilitator::return_id())->institution_id],
            ['id','!=',Facilitator::return_id()],
        ])->pluck('id');
    }

    public static function return_facilitator_all_courses(): array
    {
        $courses= Course::where([
            ['publishable_id',Facilitator::return_id()],
            ['publishable_type',"App\Models\Facilitator"],
            ['upload_status',1]
        ])
            ->orWhere([
                ['publishable_id',Facilitator::find(Facilitator::return_id())->institution_id],
                ['publishable_type', "App\Models\Institution"],
                ['upload_status',1],
                ['is_public',1],
                ['is_draft',0]
            ])
            ->orWhere(function ($q) {
                $q->whereIn('publishable_id',Facilitator::return_other_facilitators())
                    ->where([
                        ['publishable_type', 'App\Models\Facilitator'],
                        ['upload_status',1],
                        ['is_public',1],
                        ['is_draft',0]
                    ]);
            });
        return [
            'courses'=>$courses,
            'courses_id'=>$courses->pluck('id'),
            'courses_topics'=>$courses->pluck('topic'),
        ];
    }

    public function school_f(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class,'institution_id','id');
    }
//    public function course_f(){
//        return $this->hasMany(Course::class);
//    }
    public function course_f(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Course::class,'publishable');
    }

    public function response_rating_f(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(responseRating::class,'rateable');
    }
}
