<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CourseController;
use App\Http\Requests\RateResponseRequest;
use App\Models\Course;
use App\Models\courseQuestion;
use App\Models\courseQuestionOption;
use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use App\Models\studentResponses;
use App\Notifications\Student\StudentResponseRatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InstitutionApiResponsesController extends Controller
{
    protected $CourseController;
    public function __construct(CourseController $CourseController){
        $this->CourseController = $CourseController;
    } // Get Cookie Controller Controller Properties

    public function get_course($id){
        if(Course::find($id)->exists())
            $course= Course::find($id);
        else
            return "false";
        if(
            !isset($course) || $course->upload_status ==0 ||
            ($course->publishable_type == $this->CourseController->institution_image && $course->publishable_id != Institution::return_id())
            ||
            ($course->publishable_type == $this->CourseController->facilitator_image && in_array($course->publishable_id ,Institution::return_facilitators_id_array()) )
        )
            return "false";
        return $course;
    }

    public function get_institution_rated_courses_response_wise(){

        try{
            $institution_courses= Institution::return_institution_uploaded_courses()->orderByDesc('id')->get();
        }catch(\Exception $e){
            return response(['message'=>'Error, Can not retrieve your published courses'],404);
        } //Check  if Courses Exist of facilitator

        $rated_course=[];
        $unrated_course=[];
        $total_rating_array= [];
        $response_array=[];
        try {
            foreach ($institution_courses as $institution_course) {

                if(!$institution_course->student_response_c()->exists()){
                    continue;
                }
                $temp_responses = $institution_course->student_response_c()->get();
                //return $temp_responses;

                if (empty($institution_course->student_response_c()->first())) {
                    $unrated_course[] = $institution_course;
                } else {
                    foreach ($temp_responses as $temp_response) {

                        $response_array[$temp_response->course_question_id]=$temp_response->student_id;

                        if (!empty($temp_response->response_rating_sr()->first())
                            && !in_array($institution_course, $unrated_course)
                            && !in_array($institution_course, $rated_course) ){

                            $temp_ratings = $temp_response->response_rating_sr()->get();
                            foreach ($temp_ratings as $temp_rating) {

                                $total_rating_array[] = $temp_rating->response_rating;

                            }
                            $total_rating = array_sum($total_rating_array) / count($total_rating_array);
                            $institution_course->avg_rating = $total_rating;
                            $rated_course[] = $institution_course;

                        } else if (empty($temp_response->response_rating_sr()->first())
                            && !in_array($institution_course, $unrated_course)) {
                            if (in_array($institution_course, $rated_course) ){

                                $rated_course = array_diff($rated_course, [$institution_course]);
                                if (isset($institution_course->avg_rating)) unset($institution_course->avg_rating);
                                $unrated_course[] = $institution_course;
                                continue;
                            }
                            $unrated_course[] = $institution_course;
                        }
                    }
                }
                $institution_course->responses_count=count($response_array);
            }

        } catch(\Exception $e){
            return response(['message'=>'Error, Can not retrieve your ratings on published courses'],404);
        } //Check  if Courses Exist of facilitator

        $rated_course= $this->CourseController->paginate($rated_course, $perPage = 7, $page = null, $options = []);
        //$unrated_course= $this->CourseController->paginate($unrated_course, $perPage = 7, $page = null, $options = []);

        return response([
            'rated courses'=>$rated_course,
        ]);
    }

    public function get_institution_unrated_courses_response_wise(){

        try{
            $institution_courses= Institution::return_institution_uploaded_courses()->where('upload_status',1)->orderByDesc('id')->get();
        }catch(\Exception $e){
            return response(['message'=>'Error, Can not retrieve your published courses'],404);
        } //Check  if Courses Exist of facilitator

        //$rated_course=[];
        $unrated_course=[];
        //$total_rating_array= [];

        try {
            foreach ($institution_courses as $institution_course) {

                if(!$institution_course->student_response_c()->exists()){
                    continue;
                }

                $temp_responses = $institution_course->student_response_c()->get();
                $institution_course->responses_count=count($temp_responses);

                if (empty($institution_course->student_response_c()->first())) {
                    $unrated_course[] = $institution_course;
                } else {
                    foreach ($temp_responses as $temp_response) {

                        if (empty($temp_response->response_rating_sr()->first())
                            && !in_array($institution_course, $unrated_course)) {

                            $unrated_course[] = $institution_course;
                        }
                    }
                }
            }
        }
        catch(\Exception $e){
            return response(['message'=>'Error, Can not retrieve your ratings on published courses'],404);
        } //Check  if Courses Exist of facilitator

        //$rated_course= $this->CourseController->paginate($rated_course, $perPage = 7, $page = null, $options = []);
        $unrated_course= $this->CourseController->paginate($unrated_course, $perPage = 7, $page = null, $options = []);

        return response([
            //'rated courses'=>$rated_course,
            'unrated courses'=>$unrated_course,
        ]);
    }

    public function get_institution_rated_or_non_rated_course_responses($id){
        $course_students=[];
        $response_count=0;
        $response_rating_count=0;
        $course_students_response=[];

        $course_student_collection = new Collection();

        $course=Course::find($id);

        if($course == null)
            return response (['Error, This Course Not Found'],500);

        if(!in_array($id,Institution::return_institution_all_courses()['courses_id']->toArray()))
            return response(['Error, This Course is not for you'],500);

        $students_responses=$course->student_response_c()->get();
        $course->response_count = count($students_responses);

        foreach ($students_responses as $students_response)
            $course_students[]=$students_response->student_id;

        $course_students= array_values(array_unique($course_students));

        foreach ($course_students as $course_student){
            $response_rating_count_arr=[];
            $question_id_arr=[];
            $temp_student=Student::find($course_student);
            $temp_student->responses=$students_responses->where('student_id',$course_student);
            $response_count=count($temp_student->responses)+$response_count;
            foreach ($temp_student->responses as $temp_student_response){
                $course_students_response[]= $temp_student_response->course_question_id;
                $temp_student_response->question= courseQuestion::find($temp_student_response->course_question_id)->question;
                $temp_student_response->question_id= courseQuestion::find($temp_student_response->course_question_id)->id;
                $question_id_arr[]=$temp_student_response->question_id;
                $temp_student_response->question_type= courseQuestion::find($temp_student_response->course_question_id)->question_type;

                if (in_array($temp_student_response->question_type,[3,5])){
                    $temp_student_response->question_options=courseQuestionOption::where('course_question_id',$temp_student_response->question_id)->get();
                    // Eiko 2022 - 06 -21
                    //$temp_student_response->student_response=courseQuestionOption::where('id',$temp_student_response->student_response)->first()->courseQuestion_option;

                    $student_response = courseQuestionOption::where('id',$temp_student_response->student_response)->first();
                    if($student_response){
                        $temp_student_response->student_response = $student_response->courseQuestion_option;
                    }
                }
                else if ($temp_student_response->question_type == 4){
                    $temp_student_response->question_options=courseQuestionOption::where('course_question_id',$temp_student_response->question_id)->get();
                    $temp_student_response->student_response=courseQuestionOption::where('id',$temp_student_response->student_response)->first()->courseQuestion_option;

                }
                if (empty($temp_student_response->response_rating_sr()->first()))
                    $temp_student_response->is_rated = 0;
                else {
                    $temp_student_response->is_rated = 1;
                    $response_rating_count++;
                    $temp_student_response->rating = $temp_student_response->response_rating_sr()->first()->response_rating;
                } // Get response ratings


            }


            $temp_student->question_id_array=array_unique($question_id_arr);
            $course_student_collection = $course_student_collection->push($temp_student);
        }


        if ($response_count == $response_rating_count  && $response_rating_count != 0)
            $course->ques_rated=2;
        // All Ques Rated
        else if($response_count > $response_rating_count && $response_rating_count != 0)
            $course->ques_rated=1;
        // Some Ques Rated
        else if( $response_count > 0 && $response_rating_count == 0)
            $course->ques_rated=0;
        //No Ques Rated

        return response([
            'courses'=>$course,
            'course students and their responses'=>$course_student_collection
        ]);
    }

    public function post_institution_rate_responses(RateResponseRequest $request){
        $response_id_s=array_keys($request->rating);
        $student_id=[];
        $user_id = Institution::return_id();

        foreach ($response_id_s as $response_id){
            $student_id[]=studentResponses::find($response_id)->student_id;
        }
        $student_id=array_unique($student_id);
        if(sizeof($student_id)>1)
            return response(['message'=>'Students Id Mismatch'],500);

        foreach ($request->rating as $response_id=>$response_rating) {

            if($response_rating==0)
                continue;

            if (!studentResponses::find($response_id))
                return response(['message' => 'Response doesnt exists'], 404);

            $student_response = studentResponses::find($response_id);

            try{
                $course = Course::find($student_response->course_question_sr()->first()->course_id);
            }
            catch(\Exception $e){
                return response(['message'=>'Error, Can not get your course'],500);
            }


            if ($course->publishable_id != $user_id || $course->publishable_type != $this->CourseController->institution_image)
                return response(['message' => 'Error, Course is not for you'], 404);


            if (!empty($student_response->response_rating_sr()->first()))
                return response(['message' => 'Already Ratings Provided'],500);
            if($response_rating!=0){
                Institution::find($user_id)->response_rating_i()->create([
                    'student_responses_id'=>$response_id,
                    'response_rating' => $response_rating
                ]);
            }
        }

        try{
            Student::find($student_id)->first()
                ->notify(new StudentResponseRatedNotification(
                    $user_id,$request->course_id,3
                ));
        } catch(\Exception $e){
            return response(['message'=>'Error Notifying User'],500);
        }

        return response (['message'=>'Ratings Provided Successfully']);

    }
}
