<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\CourseController;
use App\Http\Requests\GetCoursesRequest;
use App\Http\Requests\StoreWatchHistoryRequest;
use App\Http\Requests\StudentPostCourseQuestionsResponsesRequest;
use App\Models\Course;
use App\Models\courseQuestion;
use App\Models\courseQuestionOption;
use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Authority;
use App\Models\studentResponses;
use App\Models\watchHistory;
use App\Notifications\Facilitator\FacilitatorResponseSubmitted;
use App\Notifications\Institution\InstitutionResponseSubmitted;
use App\Notifications\Student\StudentAllChaptersOfTopicWatchedNotification;
use App\Notifications\Student\StudentAllEpisodesOfChapterWatchedNotification;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class StudentApiWatchController extends Controller
{
    protected $CourseController ,$CacheController , $student, $cache_type_student_get_courses ,$cache_type_student_get_topics, $cache_type_student_get_demo_courses ,$cache_type_student_get_demo_topics;

    public function __construct(CourseController $CourseController, CacheController $CacheController){
        $this->CourseController = $CourseController;
        $this->CacheController = $CacheController;
        $this->student=Auth::guard('api-student')->user();
        $this->cache_type_student_get_topics='student_get_topics';
        $this->cache_type_student_get_courses='student_get_courses';

    } // Get Cookie Controller Controller Properties

    public function store_watch_history($id){
        if(Course::find($id) == null)
            return response(['Error, This Course Not Found'],404);

        if(!in_array($id,Student::get_all_courses()['id']) && !in_array($id,Student::get_all_demo_courses()['id']))
            return response(['Error, This Course is not for you'],500);

        $course=Course::find($id);
        if($course== null)
            return response(['message'=>'Course Doesnt Exists' ],500);

        $student=Student::find(Student::return_id());
        $course_chapter_id_array= Course::where('topic',$course->topic)->pluck('id')->toArray();
        $std_watch_history_id_array= watchHistory::where('student_id',Student::return_id())->pluck('course_id')->toArray();
        $course_chapter_id_array_count=count($course_chapter_id_array);
        $course_episode_id_array_count=Course::where([['topic',$course->topic],['chapter',$course->chapter]])->count();


        try {
            $this->CacheController->forget_pagination_cache($student->id,$student->email,$this->cache_type_student_get_courses,1);
            $history = watchHistory::where([['student_id', Student::return_id()],['course_id',$id]]);
            if ($history->first()) {
                $history->update(['updated_at' => NOW()]);
            } else {
                $watch_history= new watchHistory();
                $watch_history->course_id=$id;
                $watch_history->student_id=Student::return_id();
                $watch_history->save();
            }
        }catch (\Exception $e){
            return response(['message'=>'Can not update History'],500);
        }

        try{
            if(!in_array($id,$std_watch_history_id_array) && array_diff($course_chapter_id_array,$std_watch_history_id_array)==[$id]){
                $student
                    ->notify(new StudentAllChaptersOfTopicWatchedNotification(
                        $id, $course_chapter_id_array_count, $course_episode_id_array_count
                    ));
            }
        } catch(\Exception $e){
            return response(['message'=>'Error Notifying User'],500);
        }

    }

    public function get_topics(){
        $cache=$this->CacheController->get_cache( $this->student->id, $this->student->email,$this->cache_type_student_get_topics);
        if($cache != null)
            return $cache;
        else {
            $topic = array_values(array_unique(Student::get_all_courses()['courses_topics']->toArray()));
            $topic = array_diff($topic, [null]);
            $topic = array_values($topic);
            $this->CacheController->make_cache( $this->student->id, $this->student->email,$topic,$this->cache_type_student_get_topics);
            return response($topic);
        }
    }

    public function get_courses(GetCoursesRequest $request){
        $all_course_id =Student::get_all_courses()['id'];
        $cache_counter=0;
        if($request["topic"] != null  && $request->has('topic') && ( !$request->has('chapter') ||  $request["chapter"] == null) && ( !$request->has('query') ||  $request["query"] == null)){
            $all_course= Course::whereIn('id',$all_course_id)->where('topic',$request->topic)->orderByDesc('id')->paginate(16);
        }

        else if($request["topic"] != null && $request["chapter"] != null && $request->has('topic') && $request->has('chapter') && (!$request["query"] ||  $request->query == null)){
            $all_course= Course::whereIn('id',$all_course_id)->where([['topic',$request->topic],['chapter',$request->chapter]])->orderByDesc('id')->paginate(16);
        }

        else if($request["query"] != null && $request->has('query') && ( !$request->has('chapter') ||  $request["chapter"] == null) && ( !$request->has('topic') ||  $request["topic"] == null)){
            $string= $request->toArray()["query"];
            $all_course=Course::whereIn('id',$all_course_id)->orderByDesc('id')->search($string)->paginate(16);
            if(count($all_course)==0)
                return response(['message'=>'No Courses Found']);
        }

        else if($request["query"] != null && $request["topic"] != null && $request->has('query') && $request->has('topic') && ( !$request->has('chapter') ||  $request["chapter"] == null)){
            $string= $request->toArray()["query"];
            $all_course=Course::whereIn('id',$all_course_id)->where('topic',$request->topic)->orderByDesc('id')->search($string)->paginate(16);
            if(count($all_course)==0)
                return response(['message'=>'No Courses Found']);

        }

        else{
            if(!$request->has('page')){
                $cache= $this->CacheController->get_pagination_cache($this->student->id,$this->student->email,1,$this->cache_type_student_get_courses);
            }else{
                $cache= $this->CacheController->get_pagination_cache($this->student->id,$this->student->email,$request->page,$this->cache_type_student_get_courses);
            }
            if($cache != null)
                return $cache;
            else{
                $cache_counter=1;
                $all_course= Course::whereIn('id',$all_course_id)->where('upload_status',1)->orderByDesc('id')->paginate(16);
            }
        }

        foreach ($all_course as $data){
            if($data->upload_status==0 || ($data->is_public == 0 && $data->is_draft== 1)){
                continue;
            }
            // Validating only respective courses

            if($data->updated_at == null){
                $data->uploaded_hours_ago = NOW()->diffInHours($data->created_at);
            }
            else{
                $data->uploaded_hours_ago = NOW()->diffInHours($data->updated_at);
            }
            $data->uploaded_days_ago=intval($data->uploaded_hours_ago/24);
            //Generating UploadedHoursAgo field

            $data->course_short_details = strstr($data->details, '.', true);
            $data->course_duration = sprintf('%02d:%02d:%02d', ($data->durationInSec/3600),($data->durationInSec/60%60), $data->durationInSec%60);
            if($data->publishable_type == $this->CourseController->facilitator_json_image){
                $temp=Facilitator::find($data->publishable_id);
                $data->facilitator_profile_pic= $temp->profile_picture;
                $data->facilitator_name= $temp->name;
                $data->is_institution= 0;
            }
            else {
                $temp=Institution::find($data->publishable_id);
                $data->institution_profile_picture= $temp->institution_profile_picture;
                $data->institution_name= $temp->institution_name;
                $data->is_institution= 1;
            }
            $watch_histories=$data->watchHistory_c();
            $data->viewes= $watch_histories->count();
            $data->is_watched=0;
            if($watch_histories->where('student_id',Student::return_id())->exists())
                $data->is_watched=1;

            // Adding temp info

            unset(
                $data->path,
                $data->laravel_through_key,
                $data->durationInSec,
                $data->is_public,
                $data->is_draft,
                $data->video_original_name,
                $data->created_at,
                $data->updated_at,
                $data->details,
                $data->facilitator_id
            ); //removing not required fields

        }
        if($cache_counter ==1)
            $this->CacheController->make_pagination_cache(
                $this->student->id,
                $this->student->email,
                $all_course,
                json_decode(json_encode($all_course))->current_page,
                $this->cache_type_student_get_courses
            );

        return response($all_course);
    }

    /**
    *  Get courses uploaded by Authority, which we define as Demo video
    */
    public function get_demo_courses(GetCoursesRequest $request){        
        $all_course_id =Student::get_all_demo_courses()['id'];
        $cache_counter=0;
        if($request["topic"] != null  && $request->has('topic') && ( !$request->has('chapter') ||  $request["chapter"] == null) && ( !$request->has('query') ||  $request["query"] == null)){
            $all_course= Course::whereIn('id',$all_course_id)->where('topic',$request->topic)->orderByDesc('id')->paginate(16);
        }

        else if($request["topic"] != null && $request["chapter"] != null && $request->has('topic') && $request->has('chapter') && (!$request["query"] ||  $request->query == null)){
            $all_course= Course::whereIn('id',$all_course_id)->where([['topic',$request->topic],['chapter',$request->chapter]])->orderByDesc('id')->paginate(16);
        }

        else if($request["query"] != null && $request->has('query') && ( !$request->has('chapter') ||  $request["chapter"] == null) && ( !$request->has('topic') ||  $request["topic"] == null)){
            $string= $request->toArray()["query"];
            $all_course=Course::whereIn('id',$all_course_id)->orderByDesc('id')->search($string)->paginate(16);
            if(count($all_course)==0)
                return response(['message'=>'No Courses Found']);
        }

        else if($request["query"] != null && $request["topic"] != null && $request->has('query') && $request->has('topic') && ( !$request->has('chapter') ||  $request["chapter"] == null)){
            $string= $request->toArray()["query"];
            $all_course=Course::whereIn('id',$all_course_id)->where('topic',$request->topic)->orderByDesc('id')->search($string)->paginate(16);
            if(count($all_course)==0)
                return response(['message'=>'No Courses Found']);

        }

        else{
            if(!$request->has('page')){
                $cache= $this->CacheController->get_pagination_cache($this->student->id,$this->student->email,1,$this->cache_type_student_get_demo_courses);
            }else{
                $cache= $this->CacheController->get_pagination_cache($this->student->id,$this->student->email,$request->page,$this->cache_type_student_get_demo_courses);
            }
            if($cache != null)
                return $cache;
            else{
                $cache_counter=1;
                $all_course= Course::whereIn('id',$all_course_id)->where('upload_status',1)->orderByDesc('id')->paginate(16);
            }
        }

        foreach ($all_course as $data){
            if($data->upload_status==0 || ($data->is_public == 0 && $data->is_draft== 1)){
                continue;
            }
            // Validating only respective courses

            if($data->updated_at == null){
                $data->uploaded_hours_ago = NOW()->diffInHours($data->created_at);
            }
            else{
                $data->uploaded_hours_ago = NOW()->diffInHours($data->updated_at);
            }
            $data->uploaded_days_ago=intval($data->uploaded_hours_ago/24);
            //Generating UploadedHoursAgo field

            $data->course_short_details = strstr($data->details, '.', true);
            $data->course_duration = sprintf('%02d:%02d:%02d', ($data->durationInSec/3600),($data->durationInSec/60%60), $data->durationInSec%60);
            if($data->publishable_type == $this->CourseController->authority_image){
                $temp=Authority::find($data->publishable_id);
                $data->authority_profile_pic= $temp->profile_picture;
                $data->authority_name= $temp->name;
                $data->is_institution= 0;
            }           
            $watch_histories=$data->watchHistory_c();
            $data->viewes= $watch_histories->count();
            $data->is_watched=0;
            if($watch_histories->where('student_id',Student::return_id())->exists())
                $data->is_watched=1;

            // Adding temp info

            unset(
                $data->path,
                $data->laravel_through_key,
                $data->durationInSec,
                $data->is_public,
                $data->is_draft,
                $data->video_original_name,
                $data->created_at,
                $data->updated_at,
                $data->details,
                $data->facilitator_id
            ); //removing not required fields

        }
        if($cache_counter ==1)
            $this->CacheController->make_pagination_cache(
                $this->student->id,
                $this->student->email,
                $all_course,
                json_decode(json_encode($all_course))->current_page,
                $this->cache_type_student_get_demo_courses
            );

        return response($all_course);
    }
    public function get_demo_topics(){
        $cache=$this->CacheController->get_cache( $this->student->id, $this->student->email,$this->cache_type_student_get_demo_topics);
        if($cache != null)
            return $cache;
        else {
            $topic = array_values(array_unique(Student::get_all_demo_courses()['courses_topics']->toArray()));
            $topic = array_diff($topic, [null]);
            $topic = array_values($topic);
            $this->CacheController->make_cache( $this->student->id, $this->student->email,$topic,$this->cache_type_student_get_demo_topics);
            return response($topic);
        }
    }
    public function get_course_detail($id){
        $submitted=0;
        $rated=0;
        $is_public=0;

        $course=Course::find($id);

        if($course == null)
            return response (['Error, This Course Not Found'],500);

        if( !in_array($id,Student::get_all_courses()['id']) && !in_array($id,Student::get_all_demo_courses()['id']))
            return response(['Error, This Course is not for you'],500);

        if($course->publishable_type == $this->CourseController->facilitator_json_image){
            $course->facilitator_pic=Facilitator::find($course->publishable_id)->profile_picture;
            $course->facilitator_name=Facilitator::find($course->publishable_id)->name;
            $course->is_institution=0;
        }
        else{
            $course->institution_pic=Institution::find($course->publishable_id)->institution_profile_picture;
            $course->institution_name=Institution::find($course->publishable_id)->institution_name;
            $course->is_institution=1;
        }
        $course_student_response =  new \Illuminate\Support\Collection();
        try{
            $course_questions =  $course->course_question_c()->get();
            foreach ($course_questions as $course_question){

                if(in_array($course_question->question_type,[3,4,5])) {
                    $course_question->options =$course_question->course_question_option_cq()->get();
                    $std_responses=$course_question->student_response_cq()->where('student_id',Student::return_id())->get();
                    $std_responses_id= $std_responses->pluck('student_response')->toArray();
                    $std_responses_id = array_map(function($value) {
                        return intval($value);
                    }, $std_responses_id);

                     foreach ($course_question-> options as $option){
                          if(in_array($option->id,$std_responses_id)){
                              $option->is_response=1;
                              $option->your_response_id= $std_responses->where('student_response',$option->id)->first()->id;
                              $course_question->your_response_id= $option->your_response_id;
                          }
                     }

                }
                $temp_responses = $course_question
                    ->student_response_cq()
                    ->get(); 
                if (isset($temp_responses)) {
                    foreach ($temp_responses as $temp_response) {
                        if ($temp_response->student_id == Student::return_id()) {
                            $is_public=$temp_response->is_public;
                            $submitted = 1;
                            if(!in_array($course_question->question_type,[3,4,5])){
                                $course_question->your_response_id = $temp_response->id;
                                $course_question->your_response = $temp_response->student_response;
                            }
                            $course_question->your_rating = $temp_response->response_rating_sr()->value('response_rating');
                            if($course_question->your_rating != null) $rated=1;
                        } else {
                            $temp_response->student_name = Student::find($temp_response->student_id)->name;
                            $temp_response->student_profile_picture = Student::find($temp_response->student_id)->profile_picture;
                            $temp_response->response_rating = $temp_response->response_rating_sr()->value('response_rating');

                            if($temp_response->is_public == 1)
                                $course_student_response->push($temp_response);

                            if(in_array($course_question->question_type,[3,4,5]))
                                $temp_response->student_response = "";
                                // Eiko 2022 - 06 -21
                                // $temp_response->student_response=courseQuestionOption::find($temp_response->student_response)->courseQuestion_option;
                                // Taher 2022 - 06 -21
                                $student_response=courseQuestionOption::find($temp_response->student_response);
                                 if($student_response){
                                    $temp_response->student_response = $student_response->courseQuestion_option;
                                 }
                        }

                        if($temp_response->updated_at == null){
                            $course->uploaded_hours_ago = NOW()->diffInHours($course->created_at);
                        }
                        else{
                            $temp_response->uploaded_hours_ago = NOW()->diffInHours($course->updated_at);
                        } // Added  new Temp Responses

                    }
                }
            }

        } 
        catch(\Exception $e){
            return response(['message'=>'Error, Course responses Not Found'],404);
        }
        // Get Course Responses with ratings
        //$top_responses=$course_student_response->sortByDesc('response_rating')->slice(0, 4);
        $top_responses=$course_student_response->sortByDesc('response_rating');
        $student_watch_count=watchHistory::where('course_id',$course->id)->count();

        if(env('AWS_ENV')){
            $file=explode('videos/', $course->path, 2)[1];
            $course->path = Storage::disk('s3_course_videos')->temporaryUrl($file, now()->addHour());
        }

        if($course->is_public==0){
            $watchable= $course->student_c()->count();
        }else{
            $watchable= Student::where('institution_id',Student::find(Student::return_id())->institution_id)->count();
        }
        if($watchable == 0)
            $division=0;
        else
            $division= $student_watch_count/$watchable;

        if($course->updated_at == null){
            $course->uploaded_hours_ago = NOW()->diffInHours($course->created_at);
        }
        else{
            $course->uploaded_hours_ago = NOW()->diffInHours($course->updated_at);
        } // Added  new Temp Responses

        return [
            'course details'=>$course,
            'submit_status'=>$submitted,
            'rating status'=>$rated,
            'public status'=>$is_public,
            'course_questions'=>$course_questions,
            'video_rating'=>intval($division * 5),
            'course student responses'=>$top_responses,

        ];
    }

    public function get_course_playlist($id){

//        $course=$this->CourseController->get_course($id);
//        if(!isset($course->id)) return response(['message'=>'Course Does not exists']);
//        $active=$this->CourseController->student_verify_single_course($id,Student::get_all_courses()['courses']);
//        if ($active == 0) return response(['message'=>'This Course is not for you']);

        $course=Course::find($id);

        if($course == null)
            return response (['Error, This Course Not Found'],500);

        if(!in_array($id,Student::get_all_courses()['id']))
            return response(['Error, This Course is not for you'],500);


        try{
            $course_videos_playlist_files_raw = array_diff(scandir(
                Storage::disk('course_videos_playlist')
                    ->getAdapter()->getPathPrefix() . $course->id),
                array('.', '..', '.ts')
            );
        }catch(\Exception $e){
            return response(['message'=>'Error, Course Video Files Not Found'],404);
        }
        // Getting playlist files

        $course_videos_playlist_files=preg_grep("/^(\.|\.\.)$|\.ts$/",
            $course_videos_playlist_files_raw, PREG_GREP_INVERT);
        // filtering out .ts files
        return response(['Videos'=>$course_videos_playlist_files]);
    }

    public function get_course_responses($id){
        $course=Course::find($id);

        if($course == null)
            return response (['Error, This Course Not Found'],500);

        if(!in_array($id,Student::get_all_courses()['id']->toArray()))
            return response(['Error, This Course is not for you'],500);


        try{
            $all_course_responses= $course->student_response_c()->get();
            $non_rated_responses=collect(new Student());
            $rated_responses=collect(new Student());
            foreach($all_course_responses as $student_response_id){
                if($student_response_id->is_public==0 && $student_response_id->student_id != Student::return_id())
                    continue;
                if(!$student_response_id->response_rating_sr()->exists()){

                    $non_rated_responses->add(studentResponses::where('id',$student_response_id->id)->first());
                } //getting non rated responses
                else{
                    $response= studentResponses::where('id',$student_response_id->id)->first();
                    $response->setAttribute('response_rating', $response->response_rating_sr()->first()->response_rating );
                    $rated_responses->add($response);
                } //getting  rated responses
            }
            $non_rated_responses=$non_rated_responses->sortByDesc('id');
            $rated_responses=$rated_responses->sortByDesc('response_rating');

        }catch(\Exception $e){
            return response(['message'=>'Error, can not get responses'],404);
        } //Check Course if Exists

        return response([
            'non rated responses'=>$non_rated_responses,
            'rated responses'=>$rated_responses
        ]);
    }


    public function post_course_question_responses(StudentPostCourseQuestionsResponsesRequest $request){

        $student_id = Student::return_id();
        $is_public=$request->is_public;
        $questions_id_qt= array_keys($request->question_type);
        $questions_id_ans= array_keys($request->answer);

        if($questions_id_qt == $questions_id_ans)
            $questions_id=$questions_id_qt;
        else
            return response(['message'=>'Invalid Responses'],500);

        foreach ($questions_id as $question_id) {

            $course_question = courseQuestion::find($question_id);

            if (!isset($course_question->id))
                return response(['message' => 'Course Question Does not exists'], 404);

            try{
                if($request->question_type["$question_id"] == 4 ){

                    $student_responses_id = $request->answer["$question_id"];

                    foreach ($student_responses_id as $student_response_id){
                        $student_response= $course_question->course_question_option_cq()->where('id',$student_response_id)->first()->id;
                        $course_question->student_response_cq()->create([
                            'student_response' => $student_response,
                            'is_public' => $is_public,
                            'student_id' => $student_id,

                        ]);
                    }
                }

                else if($request->question_type["$question_id"] == 3 || $request->question_type["$question_id"] == 5){
                    $student_response= $course_question->course_question_option_cq()->where('id',$request->answer["$question_id"])->first()->id;

                    try {
                        $course_question->student_response_cq()->create([
                            'student_response' => $student_response,
                            'is_public' => $is_public,
                            'student_id' => $student_id,

                        ]);
                    } catch (QueryException $e){
                        $errorCode = $e->errorInfo[1];
                        if($errorCode == 1062)
                            return response(['message' => 'Error Occurred, You have already responded to this.'], 500);

                    } catch (\Exception $e) {
                        return response(['message' => 'Error Occurred, can not store course details'], 500);
                    }

                }

                else{
                    $student_response=$request->answer["$question_id"];
                    $model_course_question=courseQuestion::find($question_id);

                    if(
                        $request->question_type["$question_id"] == 1
                        && $model_course_question->answer_length != null
                        && strlen($student_response) > $model_course_question->answer_length
                    )
                        return response(['message'=>'Error, Text-field Answer Must be less than '.$model_course_question->answer_length].' words',500);

                    else if(
                        $request->question_type["$question_id"] == 2
                        && $model_course_question->answer_min_length != null
                        && $model_course_question->answer_max_length != null
                        && (
                            (int)$request->answer["$question_id"] < $model_course_question->answer_min_length
                        || (int)$request->answer["$question_id"] > $model_course_question->answer_max_length
                        )
                    )
                        return response(['message'=>'Error, Number-field Answer Must be in between '. $model_course_question->answer_min_length.' - '.$model_course_question->answer_max_length],500);


                    try {
                        $course_question->student_response_cq()->create([
                            'student_response' => $student_response,
                            'is_public' => $is_public,
                            'student_id' => $student_id,

                        ]);
                    } catch (QueryException $e){
                        $errorCode = $e->errorInfo[1];
                        if($errorCode == 1062)
                            return response(['message' => 'Error Occurred, You have already responded to this.'], 500);

                    } catch (\Exception $e) {
                        return response(['message' => 'Error Occurred, can not store course details'], 500);
                    }

                }



            }  catch (\Exception $e) {
                return response(['message' => 'Error Occurred, can not get your responses'], 500);
            } // Get Responses Accordingly


        } //Storing Responses

        try{
            if(Course::find($request->course_id)->publishable_type == $this->CourseController->facilitator_image){
                $facilitator_id=Course::find($request->course_id)->publishable_id;
                Facilitator::find($facilitator_id)
                    ->notify(new FacilitatorResponseSubmitted(
                        $request->course_id,
                        Student::return_id(),
                    ));
            }
            else if(Course::find($request->course_id)->publishable_type == $this->CourseController->institution_image){
                $institution_id=Course::find($request->course_id)->publishable_id;
                Institution::find($institution_id)
                    ->notify(new InstitutionResponseSubmitted(
                        $request->course_id,
                        Student::return_id(),
                    ));
            }

        }catch(\Exception $e){
            return response(['message'=>'Error Notifying The Facilitator'],500);
        }

        return response(['message'=>'response added successfully']);
    }
}
