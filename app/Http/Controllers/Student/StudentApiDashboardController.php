<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Models\Course;
use App\Models\courseQuestion;
use App\Models\courseQuestionOption;
use App\Models\Student;
use App\Models\studentResponses;
use App\Models\watchHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class StudentApiDashboardController extends Controller
{
    protected $CacheController ,$student ,$cache_type_student_last_watched_video ,$cache_type_student_progress;
    public function __construct(CacheController $CacheController){
        $this->CacheController=$CacheController;
        $this->cache_type_student_last_watched_video='student_last_watched_video';
        $this->cache_type_student_progress='student_progress';
        $this->student=Auth::guard('api-student')->user();
    }
    public function get_student_latest_responses(){


        $course_std_responses=Student::find(Student::return_id())->studentResponses_st()->groupBy(['student_id','course_question_id'])->orderByDesc('id')->get();


        foreach($course_std_responses as $course_std_response){

            try{
                $course_std_response->question= $course_std_response->course_question_sr()->first()->question;
                $course_std_response->question_type= $course_std_response->course_question_sr()->first()->question_type;
            }
                //get question for response
            catch(\Exception $e){
                return response(['message'=>'Error, Can not retrieve your ratings on published courses']);
            }

            if(in_array($course_std_response->question_type,[3,5])){
                $course_std_response->student_response= courseQuestionOption::find($course_std_response->student_response)->courseQuestion_option;
            }

            else if($course_std_response->question_type == 4){
                $multiple_responses= studentResponses::where([['course_question_id',$course_std_response->course_question_id],['student_id',Student::return_id()]])->get();
                foreach ($multiple_responses as $multiple_response){
                    $multiple_response->student_response= courseQuestionOption::find($multiple_response->student_response)->courseQuestion_option;
                }

                $course_std_response->student_response= $multiple_responses;
            }

            try {
                if (empty($course_std_response->response_rating_sr()->first()))
                    $course_std_response->is_rated = 0;
                else {
                    $course_std_response->is_rated = 1;
                    $course_std_response->rating = $course_std_response->response_rating_sr()->first()->response_rating;
                } // Get response ratings
            }
            catch(\Exception $e){
                return response(['message'=>'Error, Can not retrieve your ratings on published courses']);
            }


            try{
                if($course_std_response->upated_at != null ) Carbon::parse($course_std_response->upated_at)->format('d F Y \a\t g:i A');
                else $course_std_response->time=Carbon::parse($course_std_response->created_at)->format('d F Y \a\t g:i A');
                //Adding time format  jS
            }
            catch(\Exception $e){
                return response(['message'=>'Error, Can not generate time format']);
            }

        }


        $array=array_slice(json_decode(json_encode($course_std_responses->sortByDesc('id')), true),0,2);
        return response(['latest responses'=>$array]);

    }

    public function get_student_last_watched_video(){
        $cache=$this->CacheController->get_cache( $this->student->id, $this->student->email,$this->cache_type_student_last_watched_video);
        if($cache != null)
            return $cache;
        else {

            try{
                $last_course_created_at = watchHistory::where('student_id', Student::return_id())->orderByDesc('created_at')->first();
                $last_course_updated_at = watchHistory::where('student_id', Student::return_id())->orderByDesc('updated_at')->first();

                if ($last_course_created_at == null) {
                    $this->CacheController->make_cache( $this->student->id, $this->student->email,0,$this->cache_type_student_last_watched_video);
                    return 0;
                }
                else if ($last_course_created_at->created_at >= $last_course_updated_at->updated_at)
                    $last_course = Course::find($last_course_created_at->course_id);
                else
                    $last_course = Course::find($last_course_updated_at->course_id);

                $this->CacheController->make_cache( $this->student->id, $this->student->email,$last_course,$this->cache_type_student_last_watched_video);
                return response($last_course);
        }catch(\Exception $e){
            return 0;
        }

        }
    }

    public function get_student_statistics(){
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        $all_days=['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $student_responses_count_array=[];
        $watch_histories_count_array=[];

        $watch_histories= watchHistory::where([
            ['student_id',Student::return_id()],
            ['created_at','>=',$weekStartDate],
            ['created_at','<=',$weekEndDate],
        ])
            ->orWhere([
                ['student_id',Student::return_id()],
                ['updated_at','>=',$weekStartDate],
                ['updated_at','<=',$weekEndDate],
            ])
            ->get();

        $student_responses= studentResponses::where([
            ['student_id',Student::return_id()],
            ['created_at','>=',$weekStartDate],
            ['created_at','<=',$weekEndDate],
        ])
            ->orWhere([
                ['student_id',Student::return_id()],
                ['updated_at','>=',$weekStartDate],
                ['updated_at','<=',$weekEndDate],
            ])
            ->groupBy(['student_id','course_question_id'])
            ->get();

        foreach ($watch_histories as $watch_history){
            if ($watch_history->created_at >= $watch_history->updated_at)
                $watch_history->last_watched=$watch_history->created_at;
            else
                $watch_history->last_watched=$watch_history->updated_at;

            $watch_history->last_watched_day=$watch_history->last_watched->format('l');
        }
        foreach ($student_responses as $student_response){
            if ($student_response->created_at >= $student_response->updated_at)
                $student_response->last_watched = $student_response->created_at;
            else
                $student_response->last_watched = $student_response->updated_at;

            $student_response->last_watched_day=$student_response->last_watched->format('l');
        }

        foreach ($all_days as $day){
            $student_responses_count_array[] = $student_responses->where('last_watched_day',$day)->count();
            $watch_histories_count_array[] = $watch_histories->where('last_watched_day',$day)->count();
        }

        return response([
            'label'=>$all_days,
            'student_responses_count'=>$student_responses_count_array,
            'watch_histories_count'=>$watch_histories_count_array,
            ]);

    }

    public function get_student_progress(){
        $cache=$this->CacheController->get_cache($this->student->id,$this->student->email,$this->cache_type_student_progress);
        if($cache != null)
            return $cache;

        try{
            $all_courses=Student::get_all_courses()['active_courses_id'];
            $all_courses_count =$all_courses->count();
            $watch_history_count= watchHistory::where('student_id',Student::return_id())->count();
            $all_course_questions_count= courseQuestion::whereIn('course_id',$all_courses)->count();
            $student_responses_count=studentResponses::where('student_id',Student::return_id())
                ->distinct('course_question_id')->count();

            $progress= intval(( ( ($watch_history_count/$all_courses_count) + ($student_responses_count/$all_course_questions_count) ) / 2 ) *100);
            $this->CacheController->make_cache($this->student->id,$this->student->email,$progress, $this->cache_type_student_progress);
            return $progress;
        }catch(\Exception $e){
            return 0;
        }
    }
}
