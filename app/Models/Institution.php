<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Billable;
use Laravel\Passport\HasApiTokens;
use Nicolaslopezj\Searchable\SearchableTrait;

class Institution extends Authenticatable
{
    use HasFactory, Notifiable,  HasApiTokens, Billable ,SearchableTrait;

    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'institutions.institution_name' => 10,
            'institutions.institution_code' => 7,
            'institutions.admin_name' => 3,
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'institution_name', 'institution_primary_student_quantity','institution_primary_facilitator_quantity',
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
     * @return Course|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\HasMany
     */


    public static function return_institution_uploaded_courses()
    {
        return Course::where([
            ['publishable_id', Auth::guard('api-institution')->user()->id],
            ['publishable_type', 'App\Models\Institution']
        ]);
    }
    public static function return_institution_demo_courses()
    {
        return Course::where([            
            ['publishable_type', 'App\Models\Authority']
        ]);
    }
    public static function return_institution_all_courses(): array
    {
        $courses= Course::where([
            ['publishable_id', Auth::guard('api-institution')->user()->id],
            ['publishable_type', 'App\Models\Institution'],
            ['upload_status',1]
        ])
            ->orWhere(function ($q) {
                $q->whereIn('publishable_id',Institution::return_facilitators_id_array())
                    ->where([
                        ['publishable_type', 'App\Models\Facilitator'],
                        ['upload_status',1],
                        ['is_public',1],
                        ['is_draft',0]
                    ]);
            })->orWhere([            
                ['publishable_type', 'App\Models\Authority'],
                ['upload_status',1]
        ]);

        return [
            'courses'=>$courses,
            'courses_id'=>$courses->pluck('id'),
            'courses_topics'=>$courses->pluck('topic'),
        ];
    }
    public static function return_facilitators()
    {
        return Facilitator::where('institution_id',Institution::return_id());
    }
    public static function return_facilitators_id_array(): array
    {
        return Facilitator::where('institution_id',Institution::return_id())->pluck('id')->toArray();
    }
    public static function return_id(){

    try{
        if(Institution::where('id',Auth::guard('api-institution')->user()->id)->exists() && Auth::guard('api-institution')->user()->token()->role==3)
            return Auth::guard('api-institution')->user()->id;
        else {
            return response(['message'=>'Unauthenticated'],401);
        }
    }catch(\Exception $e){
        return response(['message'=>'Unauthenticated'],401);
    }
    }

    public static function return_stripe_id(){

        if(Institution::where('email',Auth::guard('api-institution')->user()->email)->first() && Auth::guard('api-institution')->user()->token()->role==3)
            return Institution::find(Institution::return_id())->stripe_id;
        else {
            return response(['message'=>'Unauthenticated'],401);
        }
    }

    public function student_s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Student::class);
    }
    public function facillitor_s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Facilitator::class);
    }
    public function invoice_s(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class);
    }
    public function course_s(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Course::class,Facilitator::class);
    }
    public function course_i(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Course::class,'publishable');
    }
    public function response_rating_i(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(responseRating::class,'rateable');
    }
    public function billing_address_i(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BillingAddress::class);
    }
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}
