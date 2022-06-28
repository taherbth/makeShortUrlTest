<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CourseController;
use App\Models\Course;
use App\Models\courseQuestionOption;
use App\Models\Student;
use App\Models\studentResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentApiResponsesController extends Controller
{

    protected $CourseController;

    public function __construct(CourseController $CourseController){
        $this->CourseController = $CourseController;

    } // Get Cookie Controller Controller Properties


    public function get_student_all_rated_responses(){
        $rated_course=[];
        $unrated_course=[];
        $course_std_response_arr=[];
        $course_response_time=null;

        try{
            $courses =Course::whereIn('id',Student::get_all_courses()['active_courses']->pluck('id'))->orderByDesc('id')->get();
        } catch(\Exception $e){
            return response(['message'=>'Can not get your courses'],500);
        }

        foreach($courses as $course){

            if(!isset($course->id))
                return response(['message'=>'Course Does not exists']);

            $course->course_questions=$course->course_question_c()->get();

            if(!$course->course_question_c()->exists())
                $unrated_course[]=$course;

            foreach ($course->course_questions as $course_question){

                $course_question->course_question_responses = $course_question->student_response_cq()->where('student_id',Student::return_id())->get();

                if(in_array($course_question->question_type,[3,5]) && $course_question->student_response_cq()->exists()){
                    //if($course_question->course_question_responses->first()->course_question_id==12) return courseQuestionOption::find($course_question->course_question_responses->first()->student_response)->courseQuestion_option;
                    //return courseQuestionOption::find($course_question->course_question_responses->first()->student_response)->first()->courseQuestion_option;
                    foreach ($course_question->course_question_responses as $course_question_response){
                        $course_question_response->student_response= courseQuestionOption::find($course_question_response->student_response)->courseQuestion_option;
                    }
                }

                else if($course_question->question_type==4  && $course_question->student_response_cq()->exists()){
                    foreach ($course_question->course_question_responses as $course_question_response){
                        $course_question_response->student_response= courseQuestionOption::find($course_question_response->student_response)->courseQuestion_option;
                    }
                }


                if(!$course_question->student_response_cq()->exists() && !in_array($course,$unrated_course) )
                    $unrated_course[]=$course;

                try{
                    foreach($course_question->course_question_responses as $course_question_response){

                        if( $course_question_response->updated_at == null && $course_question_response->created_at != null){

                            if($course_response_time<$course_question_response->created_at)
                                $course_response_time=$course_question_response->created_at;

                        }
                        else if ($course_question_response->updated_at != null){

                            if($course_response_time<$course_question_response->updated_at)
                                $course_response_time=$course_question_response->updated_at;

                        } // Added  new Temp Responses

                        if (empty($course_question_response->response_rating_sr()->first())){
                            $course_question_response->is_rated = 0;
                            if (!in_array($course,$unrated_course) && !in_array($course,$rated_course)){
                                $unrated_course[]=$course;
                            }
                            else if(!in_array($course,$unrated_course) && in_array($course,$rated_course)){
                                $rated_course=array_diff($rated_course, [$course]);
                                $unrated_course[]=$course;
                            }
                        }
                        else {
                            $course_question_response->is_rated = 1;
                            $course_question_response->rating = $course_question_response->response_rating_sr()->first()->response_rating;
                            if (!in_array($course,$unrated_course) && !in_array($course,$rated_course))
                                $rated_course[]=$course;
                        } // Get response ratings
                    }
                } catch (\Exception $e){
                    return response(['Error'=>'Can Not Retrieve Rated or Unrated Courses'],500);
                }
            }
            $course->response_time_latest= 'Last Updated '.Carbon::parse($course_response_time)->format('d F Y \a\t g:i A');
            // Custom Time Format
        }
        $rated_course=array_unique($rated_course);
        $rated_course= $this->CourseController->paginate($rated_course, $perPage = 4, $page = null, $options = []);
        //$unrated_course= $this->CourseController->paginate($unrated_course, $perPage = 4, $page = null, $options = []);
        //Custom Pagination

        return response([
            'rated courses'=>$rated_course,
            //'unrated courses'=>$unrated_course,
        ]);
    }

    public function get_student_all_unrated_responses(){
       // $rated_course=[];
        $unrated_course=[];
        $course_response_time=null;

        try{
            $courses =Course::whereIn('id',Student::get_all_courses()['active_courses']->pluck('id'))->orderByDesc('id')->get();
        } catch(\Exception $e){
            return response(['message'=>'Can not get your courses'],500);
        }

        foreach($courses as $course){

            if(!isset($course->id))
                return response(['message'=>'Course Does not exists']);

            $course->course_questions=$course->course_question_c()->get();

            if(!$course->course_question_c()->exists())
//                $unrated_course[]=$course;
                continue;

            foreach ($course->course_questions as $course_question){

                $course_question->course_question_responses = $course_question->student_response_cq()->where('student_id',Student::return_id())->get();

                if(!$course_question->student_response_cq()->exists() && !in_array($course,$unrated_course) )
                   // $unrated_course[]=$course;
                    continue;

                else if(in_array($course_question->question_type,[3,5]) && $course_question->student_response_cq()->exists()){
                    //if($course_question->course_question_responses->first()->course_question_id==12) return courseQuestionOption::find($course_question->course_question_responses->first()->student_response)->courseQuestion_option;
                        //return courseQuestionOption::find($course_question->course_question_responses->first()->student_response)->first()->courseQuestion_option;
                    foreach ($course_question->course_question_responses as $course_question_response){
                        $course_question_response->student_response= courseQuestionOption::find($course_question_response->student_response)->courseQuestion_option;
                    }
                }

                else if($course_question->question_type==4  && $course_question->student_response_cq()->exists()){
                    foreach ($course_question->course_question_responses as $course_question_response){
                        $course_question_response->student_response= courseQuestionOption::find($course_question_response->student_response)->courseQuestion_option;
                    }
                }

                foreach($course_question->course_question_responses as $course_question_response){

                    if( $course_question_response->updated_at == null && $course_question_response->created_at != null){

                        if($course_response_time<$course_question_response->created_at)
                            $course_response_time=$course_question_response->created_at;

                    }
                    else if ($course_question_response->updated_at != null){

                        if($course_response_time<$course_question_response->updated_at)
                            $course_response_time=$course_question_response->updated_at;

                    } // Added  new Temp Responses

                    if (empty($course_question_response->response_rating_sr()->first())){
                        $course_question_response->is_rated = 0;
//                        if (!in_array($course,$unrated_course) && !in_array($course,$rated_course)){
                            $unrated_course[]=$course;
//                        }
//                        else if(!in_array($course,$unrated_course) && in_array($course,$rated_course)){
//                            $rated_course=array_diff($rated_course, [$course]);
//                            $unrated_course[]=$course;
//                        }
                    }
//                    else {
//                        $course_question_response->is_rated = 1;
//                        $course_question_response->rating = $course_question_response->response_rating_sr()->first()->response_rating;
//                        if (!in_array($course,$unrated_course) && !in_array($course,$rated_course))
//                            $rated_course[]=$course;
//                    } // Get response ratings
                }
            }
            $course->response_time_latest= 'Last Updated '.Carbon::parse($course_response_time)->format('d F Y \a\t g:i A');
            // Custom Time Format
        }
        $unrated_course=array_unique($unrated_course);

        //$rated_course= $this->CourseController->paginate($rated_course, $perPage = 4, $page = null, $options = []);
        $unrated_course= $this->CourseController->paginate($unrated_course, $perPage = 4, $page = null, $options = []);
        //Custom Pagination

        return response([
            //'rated courses'=>$rated_course,
            'unrated courses'=>$unrated_course,
        ]);
    }

    public function post_edit_course_responses(Request $request){
        $question_id_array=[];
        $auth_id=Student::return_id();

        foreach ($request->response_type as $response_id=>$response_type){
            $student_responses_db=studentResponses::where([['student_id',$auth_id],['id',$response_id]])->first();
            try{
            if($student_responses_db->response_rating_sr()->exists()) return response(['message'=>'Error, Your Response is already Rated by Your Facilitator'],500);
            $student_responses_db->update([
                'student_response'=>$request->answer[$response_id],
                'is_public'=>$request->is_public,
                'created_at'=>$request->created_at,
            ]);
            }catch(\Exception $e){
                return $student_responses_db;
            }
        }

        if($request->has('delete_response_options') || $request->delete_response_options != null){
            foreach ($request->delete_response_options as $multi_response_id=>$question_id){
                $multi_response=studentResponses::find($multi_response_id);
                if($multi_response->response_rating_sr()->exists()) return response(['message'=>'Error, Your Response is already Rated by Your Facilitator'],500);
                $multi_response->delete();
                $question_id_array[]=$question_id;
            }
        }

        $question_id_array=array_unique($question_id_array);
        foreach ($question_id_array as $question_unique_id){
            $answers=$request->answer[$question_unique_id];
            foreach ($answers as $answer){
                studentResponses::create([
                    'student_response'=>$answer,
                    'course_question_id'=>$question_unique_id,
                    'student_id'=>$auth_id,
                    'is_public'=>$request->is_public,
                    'created_at'=>$request->created_at,
                ]);
            }
        }

//        foreach ($request->delete_response_options as $multi_response_id=>$question_id){
//            $multi_response=studentResponses::find($multi_response_id);
//            if($multi_response->response_rating_sr()->exists()) return response(['message'=>'Error, Your Response is already Rated by Your Facilitator'],500);
//            $multi_response->delete();
//            $answers=$request->answer[$question_id];
//
//            foreach ($answers as $answer){
//                studentResponses::create([
//                    'student_response'=>$answer,
//                    'course_question_id'=>$question_id,
//                    'student_id'=>$auth_id,
//                    'is_public'=>$request->is_public,
//                    'created_at'=>$request->created_at,
//                ]);
//            }
//        }

        return response(['message'=>'Response, Updated Successfully']);
    }
}
