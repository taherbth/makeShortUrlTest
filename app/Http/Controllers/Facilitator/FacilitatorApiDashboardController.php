<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\CourseController;
use App\Models\Course;
use App\Models\courseQuestion;
use App\Models\courseQuestionOption;
use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\studentResponses;
use App\Models\watchHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacilitatorApiDashboardController extends Controller
{
    protected $CacheController ,$facilitator ,$cache_type_facilitator_last_published_video, $cache_type_facilitator_progress;
    public function __construct(CacheController $CacheController){
        $this->CacheController=$CacheController;
        $this->cache_type_facilitator_last_published_video='facilitator_last_published_video';
        $this->cache_type_facilitator_progress='facilitator_progress';
        $this->facilitator=Auth::guard('api-facilitator')->user();
    }

    public function get_facilitator_latest_responses(){

        $all_courses_response=collect(new studentResponses());
        try {
            $courses = Facilitator::return_facilitator_uploaded_courses()->get();
            //Get all courses of facilitator
        }
        catch(\Exception $e){
            return response(['message'=>'Error, Can not retrieve your courses'],404);
        }

        foreach ($courses as $course){

            if(!empty($course->student_response_c()->orderByDesc('id')->first())){

                try {
                    $course_std_responses = $course->student_response_c()->groupBy(['student_id','course_question_id'])->get();

                } catch(\Exception $e){
                    return response(['message'=>'Error, Can not get responses from your published courses']);
                } //get all responses of a course

                foreach($course_std_responses as $course_std_response){

                    unset($course_std_response->laravel_through_key);
                    try{
                        $course_std_response->question_type= $course_std_response->course_question_sr()->first()->question_type;
                        $course_std_response->question= $course_std_response->course_question_sr()->first()->question;
                    }
                        //get question for response
                    catch(\Exception $e){
                        return response(['message'=>'Error, Can not retrieve your questions on published courses']);
                    }
                    if(in_array($course_std_response->question_type,[3,5])){
                        $course_std_response->student_response= courseQuestionOption::find($course_std_response->student_response)->courseQuestion_option;
                    }

                    else if($course_std_response->question_type == 4){
                        $multiple_responses= studentResponses::where('course_question_id',$course_std_response->course_question_id)->get();
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
                        if($course_std_response->upated_at) Carbon::parse($course_std_response->upated_at)->format('d F Y \a\t g:i A');
                        else $course_std_response->time=Carbon::parse($course_std_response->created_at)->format('d F Y \a\t g:i A');
                        //Adding time format
                    }
                    catch(\Exception $e){
                        return response(['message'=>'Error, Can not generate time format']);
                    }

                    try{
                        $all_courses_response->add($course_std_response);
                    }
                        //Update Response
                    catch(\Exception $e){
                        return response(['message'=>'Error, Can not update responses'],500);
                    }
                }
            }
        }


        $top_two_latest_response=array_slice(json_decode(json_encode($all_courses_response->sortByDesc('id')), true),0,2);

        return response(['latest responses'=>$top_two_latest_response]);

    }

    public function get_facilitator_last_published_video(){
        $cache=$this->CacheController->get_cache( $this->facilitator->id, $this->facilitator->email,$this->cache_type_facilitator_last_published_video);
        if($cache != null)
            return $cache;
        else {
            try{
                $courses = Facilitator::return_facilitator_uploaded_courses();
                if (empty($courses->first()))
                    return response(['message' => 'No Courses Found']);
                $last_course_created_at = $courses->where('upload_status', 1)->orderByDesc('created_at')->first();
                $last_course_updated_at = $courses->where('upload_status', 1)->orderByDesc('updated_at')->first();

                if ($last_course_created_at->created_at >= $last_course_updated_at->updated_at)
                    $last_course = $last_course_created_at;
                else
                    $last_course = $last_course_updated_at;

                if (isset($last_course)) {
                    $this->CacheController->make_cache( $this->facilitator->id, $this->facilitator->email,$last_course,$this->cache_type_facilitator_last_published_video);
                    return response($last_course);
                }
                else {
                    $this->CacheController->make_cache( $this->facilitator->id, $this->facilitator->email,0,$this->cache_type_facilitator_last_published_video);
                    return 0;
                }
            }catch(\Exception $e){
                return 0;
            }
        }
    }

    public function get_facilitator_statistics()
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
        $student_responses_count_array=[];
        $watch_histories_count_array=[];
        $all_days=['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $facilitator_course_responses=new Collection();

        $facilitator_courses_id=Facilitator::return_facilitator_uploaded_courses()->pluck('id');
        $facilitator_courses = Course::whereIn('id',$facilitator_courses_id)->get();
        $GLOBALS["start"]=$weekStartDate;
        $GLOBALS["end"]=$weekEndDate;
         $watch_histories =  watchHistory::whereIn('course_id', $facilitator_courses_id)
             ->where(function ($q) {
                 $q->where([
                    ['created_at','>=',$GLOBALS["start"]],
                    ['created_at','<=',$GLOBALS["end"]]
                ])
                ->orWhere([
                    ['updated_at','>=',$GLOBALS["start"]],
                    ['updated_at','<=',$GLOBALS["end"]],
                ]);
             })
            ->get();



        foreach ($facilitator_courses as $facilitator_course){
            if($facilitator_course -> student_response_c()->exists()){
                $responses =$facilitator_course -> student_response_c()-> get();
                foreach ($responses as $response){
                    if(
                        ($response->created_at >= $weekStartDate
                        && $response->created_at <= $weekEndDate)
                        ||
                        ($response->updated_at >= $weekStartDate
                        && $response->updated_at <= $weekEndDate)
                    )
                        $facilitator_course_responses->push($response);
                }
            }
        }


        foreach ($watch_histories as $watch_history) {
            if ($watch_history->created_at >= $watch_history->updated_at)
                $watch_history->last_watched = $watch_history->created_at;
            else
                $watch_history->last_watched = $watch_history->updated_at;

            $watch_history->last_watched_day=$watch_history->last_watched->format('l');

        }

        foreach ($facilitator_course_responses as $facilitator_course_response) {
            if ($facilitator_course_response->created_at >= $facilitator_course_response->updated_at)
                $facilitator_course_response->last_watched = $facilitator_course_response->created_at;
            else
                $facilitator_course_response->last_watched = $facilitator_course_response->updated_at;

            $facilitator_course_response->last_watched_day=$facilitator_course_response->last_watched->format('l');

        }

        foreach ($all_days as $day){
            $student_responses_count_array[] = $facilitator_course_responses->where('last_watched_day',$day)->count();
            $watch_histories_count_array[] = $watch_histories->where('last_watched_day',$day)->count();
        }

        return response([
            'label'=>$all_days,
            'student_responses_count'=>$student_responses_count_array,
            'watch_histories_count'=>$watch_histories_count_array,
        ]);

    }

    public function get_facilitator_progress(){
        $cache=$this->CacheController->get_cache($this->facilitator->id,$this->facilitator->email,$this->cache_type_facilitator_progress);
        if($cache != null)
            return $cache;

        try{
            $private_students_count=0;
            $facilitator=Facilitator::find(Facilitator::return_id());

            $all_courses_public=$facilitator->course_f()->where([['upload_status',1],['is_public',1],['is_draft',0]]);
            $all_courses_public_id=$all_courses_public->pluck('id');
            $all_courses_public_count =$all_courses_public->count();
            $watch_history_public_count= watchHistory::whereIn('course_id',$all_courses_public_id)->count();

            $all_courses_private=$facilitator->course_f()->where([['upload_status',1],['is_public',0],['is_draft',0]]);
            $all_courses_private_id=$all_courses_private->pluck('id');
            $all_courses_private_count=$all_courses_private->count();
            $watch_history_private_count= watchHistory::whereIn('course_id',$all_courses_private_id)->count();

            if($watch_history_public_count==0 && $watch_history_private_count==0)
                return 0;

            foreach($all_courses_private_id as $all_course_private_id){
                $private_students_count+= Course::find($all_course_private_id)->student_c()->count();
            }


            $students=Institution::find( $facilitator->institution_id)->student_s();
            $students_count=$students->count();

            $total_watch_history_count= $watch_history_public_count+$watch_history_private_count;
            $total_expected_watch_history_count=($all_courses_public_count * $students_count)+($all_courses_private_count *$private_students_count);

            $all_course_public_questions=courseQuestion::whereIn('course_id',$all_courses_public_id);
            $all_course_public_questions_count= $all_course_public_questions->count();
            $all_course_public_questions_id= $all_course_public_questions->pluck('id');

            $all_course_private_questions=courseQuestion::whereIn('course_id',$all_courses_private_id);
            $all_course_private_questions_count= $all_course_private_questions->count();
            $all_course_private_questions_id= $all_course_private_questions->pluck('id');

            $student_distinct_responses= studentResponses::whereIn('course_question_id',$all_course_public_questions_id)
                ->orWhereIn('course_question_id',$all_course_private_questions_id)
                ->groupBy('course_question_id','student_id')
                ->get();

            $student_distinct_responses_count= $student_distinct_responses->count();
            $total_expected_questions_count=($all_course_public_questions_count * $students_count) + ($all_course_private_questions_count * $private_students_count);

            $progress= intval(( ( ($total_watch_history_count/$total_expected_watch_history_count) + ($student_distinct_responses_count/$total_expected_questions_count) ) / 2 ) *100);

            $this->CacheController->make_cache($this->facilitator->id,$this->facilitator->email,$progress,$this->cache_type_facilitator_progress);
            return $progress;
        } catch(\Exception $e){
            return 0;
        }
    }

}
