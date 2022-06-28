<?php

namespace App\Models;

use App\Notifications\Student\Auth\ResetPassword;
use App\Notifications\Student\Auth\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Nicolaslopezj\Searchable\SearchableTrait;

class Student extends Authenticatable
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
            'students.name' => 10,
            'students.email' => 7,
        ],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'name', 'email', 'password','institution_url','profile_picture','institution_id','email_verified_at',
        'name','profile_picture','email_verified_at',
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


    public static function return_id(){

        try{
            if(Student::where('email',Auth::guard('api-student')->user()->email)->first() && Auth::guard('api-student')->user()->token()->role==2)
                return Auth::guard('api-student')->user()->id;
            else {
                return response(['message'=>'Unauthenticated'],401);
            }
        }catch(\Exception $e){
            return response(['message'=>'Unauthenticated'],401);
        }

    }

    public static function get_all_courses(): array
    {
        $id_array=[];
        $student_courses_collection =new Collection();
        $institution_id = Student::find(Student::return_id())->institution_id;

        $facilitator_id = Facilitator::where('institution_id',$institution_id)
            ->pluck('id');

        $authority_id = Authority::pluck('id');

        $GLOBALS['institution_id']=$institution_id;
        $GLOBALS['facilitator_id']=$facilitator_id;
        $GLOBALS['authority_id']=$authority_id;


        $student_courses=Course::where([
            ['is_public',1],
            ['is_draft',0],
            ['upload_status',1]
            ])->where(function ($q) {
                    $q->where([
                        ['publishable_id',$GLOBALS['institution_id']],
                        ['publishable_type', 'App\Models\Institution']
                    ])->orWhere(function ($q1) {
                        $q1->whereIn('publishable_id',$GLOBALS['facilitator_id'])
                            ->where('publishable_type', 'App\Models\Facilitator');
                    });
        })->get();

        foreach($student_courses as $student_course){
            unset($student_course->laravel_through_key);
            $id_array[]=$student_course->id;
        }

        $private_courses= Student::find(Auth::guard('api-student')->user()->id)
            ->course_c()
            ->where([
                ['is_public',0],
                ['is_draft',0],
                ['upload_status',1]
            ])
            ->get();
        $student_courses_collection->push($student_courses);
        foreach($private_courses as $private_course){
            if(!in_array($private_course->id,$id_array,true)){
                $id_array[]=$private_course->id;
                unset($private_course->pivot);
                $student_courses_collection->merge($private_course);
            }
        }
        // $authority_courses = Course::where([           
        //     ['upload_status',1]
        //     ])->where(function ($q) {
        //             $q->where([
        //                 ['publishable_id',$GLOBALS['authority_id']],
        //                 ['publishable_type', 'App\Models\Authority']
        //             ]);
        // })->get();

        // foreach($authority_courses as $authority_course){
        //     if(!in_array($authority_course->id,$id_array,true)){
        //         $id_array[]=$authority_course->id;
        //         unset($authority_course->pivot);
        //         $student_courses_collection->merge($authority_course);
        //     }
        // }
        return [
            'courses'=>$student_courses_collection,
            'active_courses'=>$student_courses->where('upload_status',1),
            'id'=>$id_array,
            'active_courses_id'=>$student_courses->where('upload_status',1)->pluck('id'),
            'courses_topics'=>$student_courses->where('upload_status',1)->pluck('topic'),
            ];
//        return $paginate;
    }

     public static function get_all_demo_courses(): array
    {
        $id_array=[];
        $authority_courses_collection =new Collection();       
        $authority_id = Authority::pluck('id');
        $GLOBALS['authority_id']=$authority_id;

        $authority_courses = Course::where([           
            ['upload_status',1]
            ])->where(function ($q) {
                    $q->where([
                        ['publishable_id',$GLOBALS['authority_id']],
                        ['publishable_type', 'App\Models\Authority']
                    ]);
        })->get();

        foreach($authority_courses as $authority_course){
            if(!in_array($authority_course->id,$id_array,true)){
                $id_array[]=$authority_course->id;
                unset($authority_course->pivot);
                $authority_courses_collection->push($authority_course);
            }
        }
        return [
            'courses'=>$authority_courses_collection,
            'active_courses'=>$authority_courses->where('upload_status',1),
            'id'=>$id_array,
            'active_courses_id'=>$authority_courses->where('upload_status',1)->pluck('id'),
            'courses_topics'=>$authority_courses->where('upload_status',1)->pluck('topic'),
            ];
//        return $paginate;
    }

    public function school_st(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class,'institution_id','id');
    }
    public function watchHistory_st(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(watchHistory::class);
    }
    public function studentResponses_st(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(studentResponses::class);
    }
    public function course_c(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Course::class,'course_student');
    }
}
