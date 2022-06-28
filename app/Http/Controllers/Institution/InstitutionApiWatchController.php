<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\CourseController;
use App\Http\Requests\GetCoursesRequest;
use App\Models\Course;
use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use App\Models\studentResponses;
use App\Models\watchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class InstitutionApiWatchController extends Controller
{
    protected $CourseController , $CacheController,$institution ,$cache_type_institution_get_topics, $cache_type_institution_get_courses;

    public function __construct(CourseController $CourseController, CacheController $CacheController){
        $this->CourseController = $CourseController;
        $this->CacheController = $CacheController;
        $this->institution=Auth::guard('api-institution')->user();
        $this->cache_type_institution_get_topics='institution_get_topics';
        $this->cache_type_institution_get_courses='institution_get_courses';
    } // Get Cookie Controller Controller Properties

    public function get_course($id){
        if(Course::find($id) != null)
            $course= Course::find($id);
        else
            return "false";

        if(
            $course->upload_status ==0 ||
            ($course->publishable_type == $this->CourseController->institution_image && $course->publishable_id != Institution::return_id())
            ||
            ($course->publishable_type == $this->CourseController->facilitator_image && !in_array($course->publishable_id ,Institution::return_facilitators_id_array()) )
            || ($course->publishable_type == $this->CourseController->facilitator_image
                && in_array($course->publishable_id ,Institution::return_facilitators_id_array())
                && (
                    $course->is_public=0
                    || $course->is_draft=1)
            )
        )
            return "false";
        return $course;
    }

    public function get_topics(){
        $cache=$this->CacheController->get_cache( $this->institution->id, $this->institution->email,$this->cache_type_institution_get_topics);
        if($cache != null)
            return $cache;
        else {
            $topic = array_values(array_unique(Institution::return_institution_all_courses()['courses_topics']->toArray()));
            $topic = array_diff($topic, [null]);
            $topic = array_values($topic);
            $this->CacheController->make_cache( $this->institution->id, $this->institution->email,$topic,$this->cache_type_institution_get_topics);
            return response($topic);
        }

    }

    public function get_courses(GetCoursesRequest $request){
        //$institution_id=Facilitator::find(Facilitator::return_id())->first()->institution_id;
        $courses = Institution::return_institution_all_courses()['courses'];
        $courses_id = Institution::return_institution_all_courses()['courses_id'];
        $cache_counter=0;

        if($request["topic"] != null  && $request->has('topic') && ( !$request->has('chapter') ||  $request["chapter"] == null) && ( !$request->has('query') ||  $request["query"] == null)) {
            $all_course = Course::whereIn('id',$courses_id)
                ->where('topic', $request["topic"])
                ->orderByDesc('id')
                ->paginate(16);
        }

        else if($request["topic"] != null && $request["chapter"] != null && $request->has('topic') && $request->has('chapter') && ($request["query"] == null ||  !$request->has('query'))){
            $all_course =Course::whereIn('id',$courses_id)
                ->where([['topic',$request["topic"]],['chapter',$request["chapter"]]])
                ->orderByDesc('id')
                ->paginate(16);
        }

        else if($request["query"] != null && $request->has('query') && ( !$request->has('chapter') ||  $request["chapter"] == null) && ( !$request->has('topic') ||  $request["topic"] == null)){
            $string= $request->toArray()["query"];
            $all_course=$courses
                ->orderByDesc('id')
                ->search($string)
                ->paginate(16);

            if(count($all_course)==0)
                return response(['message'=>'No Courses Found']);
        }

        else if($request["query"] != null && $request["topic"] != null && $request->has('query') && $request->has('topic') && ( !$request->has('chapter') ||  $request["chapter"] == null)){
            $string= $request->toArray()["query"];
            $all_course=Course::whereIn('id',$courses_id)
                ->where('topic',$request["topic"])
                ->orderByDesc('id')
                ->search($string)
                ->paginate(16);

            if(count($all_course)==0)
                return response(['message'=>'No Courses Found']);
        }

        else{
            if(!$request->has('page')){
                $cache=$this->CacheController->get_pagination_cache( $this->institution->id, $this->institution->email,1,$this->cache_type_institution_get_courses);
            }else{
                $cache=$this->CacheController->get_pagination_cache( $this->institution->id, $this->institution->email,$request->page,$this->cache_type_institution_get_courses);
            }
            if($cache != null)
                return $cache;
            else {
                $cache_counter=1;
                $all_course = $courses->orderByDesc('id')
                    ->paginate(16);
            }
        }


        foreach ($all_course as $data){
            if($data->publishable_id != Institution::return_id()
                && $data->publishable_type != $this->CourseController->institution_json_image
                && ($data->is_public == 0 || $data->is_draft== 1)){
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
            $data->viewes= $data->watchHistory_c()->count();
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
                $data->publishable_id
            ); //removing not required fields

        }
        if($cache_counter==1)
            $this->CacheController->make_pagination_cache(
                $this->institution->id,
                $this->institution->email,
                $all_course,
                json_decode(json_encode($all_course))->current_page,
                $this->cache_type_institution_get_courses
            );
        return $all_course;
    }

    public function get_course_detail($id){
        $self_course=0;
        $positive_rating=0;
        $negative_rating=0;
        $course=Course::find($id);
        $avg_rating=[];

        if($course == null)
            return response (['Error, This Course Not Found'],500);

        if(!in_array($id,Institution::return_institution_all_courses()['courses_id']->toArray()))
            return response(['Error, This Course is not for you'],500);

        if($course->publishable_id == Institution::return_id() && $course->publishable_type==$this->CourseController->institution_json_image)
            $self_course=1;

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

        $course->total_views= $course->watchHistory_c()->count();

        if(env('AWS_ENV')){
            $file=explode('videos/', $course->path, 2)[1];
//            $course->path = Storage::disk('s3_course_videos')->getAwsTemporaryUrl(Storage::disk('s3_course_videos')->getDriver()->getAdapter(),$file, now()->addHour(),[]);
            $course->path = Storage::disk('s3_course_videos')->temporaryUrl($file, now()->addHour());


//            $file=explode('videos/', $course->path, 2)[1];
//            $client = Storage::disk('s3_course_videos')->getDriver()->getAdapter()->getClient();
//            $bucket = Config::get('filesystems.disks.s3.bucket');
//            $command = $client->getCommand('GetObject', [
//                'Bucket' => $bucket,
//                'Key' => $file  // file name in s3 bucket which you want to access
//            ]);
//            $requestt = $client->createPresignedRequest($command, '+20 minutes');
//            $course->path = (string)$requestt->getUri();
        }

//        if($this->CourseController->validate_institution($course->id) == 0 )
//            return response(['message'=>'Error, Course is not for you'],404);


        $course_student_response =  new \Illuminate\Support\Collection();
        try{
            $course_questions =  $course->with('course_question_c')->get()->where('id',$id)->first();
            foreach ($course_questions->course_question_c as $course_question){

                if($self_course==1)
                    $temp_responses = $course_question->student_response_cq()->get();

                else
                    $temp_responses = $course_question->student_response_cq()->where('is_public', 1)->get();

                if(isset($temp_responses)){
                    foreach($temp_responses as $temp_response){
                        $temp_response->student_name= Student::find($temp_response->student_id)->name;
                        $temp_response->student_profile_picture= Student::find($temp_response->student_id)->profile_picture;
                        $temp_response->response_rating=$temp_response->response_rating_sr()->value('response_rating');

                        if($temp_response->updated_at == null){
                            $course->uploaded_hours_ago = NOW()->diffInHours($course->created_at);
                        }
                        else{
                            $temp_response->uploaded_hours_ago = NOW()->diffInHours($course->updated_at);
                        } // Added  new Temp Responses

                        $course_student_response->push($temp_response) ;

                        if($temp_response->response_rating != null){
                            $avg_rating[]=$temp_response->response_rating;
                            if($temp_response->response_rating >2)
                                $positive_rating++;
                            else
                                $negative_rating++;

                        }
                    }
                }
            }

            if(empty($avg_rating))
                $avg_rating=0;
            else
                $avg_rating=array_sum($avg_rating) / count($avg_rating);

            if($positive_rating==0 && $negative_rating==0){
                $positive_sentiment=0;
                $negative_sentiment=0;
            }
            else{
                $positive_sentiment=$positive_rating/($positive_rating+$negative_rating) * 100;
                $negative_sentiment=$negative_rating/($positive_rating+$negative_rating) * 100;
            }

            $student_watch_count=watchHistory::where('course_id',$course->id)->count();

            if($course->is_public==0){
                $watchable= $course->student_c()->count();
            }else{
                $watchable= Student::where('institution_id',Institution::return_id())->count();
            }
            if($watchable == 0)
                $division=0;
            else
                $division= $student_watch_count/$watchable;

        }  catch(\Exception $e){
            return response(['message'=>'Error, Course responses Not Found'],404);
        } // Get Course Responses with ratings

        //$top_responses=$course_student_response->sortByDesc('response_rating')->slice(0, 4);
        $top_responses=$course_student_response->sortByDesc('response_rating');
        //$top_three_responses=$top_responses->slice(0,3);
        if($course->updated_at == null){
            $course->uploaded_hours_ago = NOW()->diffInHours($course->created_at);
        }
        else{
            $course->uploaded_hours_ago = NOW()->diffInHours($course->updated_at);
        } // Added  new Temp Responses

        return [
            'course details'=>$course,
            'course student responses'=>$top_responses,
            'avg _rating'=>$avg_rating,
            'positive_sentiment'=>$positive_sentiment,
            'negative_sentiment'=>$negative_sentiment,
            'video_rating'=>intval($division * 5),
            'video_insight'=>intval($division * 100),
            // 'top 3 responses'=>$top_three_responses
        ];
    }

    public function get_course_playlist($id){

        $course=Course::find($id);

        if($course == null)
            return response (['Error, This Course Not Found'],500);

        if(!in_array($id,Institution::return_institution_all_courses()['courses_id']->toArray()))
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

        if(!in_array($id,Institution::return_institution_all_courses()['courses_id']->toArray()))
            return response(['Error, This Course is not for you'],500);


        try{
            $all_course_responses= $course->student_response_c()->get();
            $non_rated_responses=collect(new Student());
            $rated_responses=collect(new Student());
            foreach($all_course_responses as $student_response_id){
                if(!$student_response_id->response_rating_sr()->exists()){

                    $non_rated_responses->add(studentResponses::where('id',$student_response_id->id)->first());
                } //getting non rated responses
                else{
                    $rated_responses->add(studentResponses::where('id',$student_response_id->id)->first());
                } //getting  rated responses
            }
            $non_rated_responses=$non_rated_responses->sortByDesc('id');
            $rated_responses=$rated_responses->sortByDesc('id');

        }catch(\Exception $e){
            return response(['message'=>'Error, can not get responses'],404);
        } //Check Course if Exists

        return response([
            'non rated responses'=>$non_rated_responses,
            'rated responses'=>$rated_responses
        ]);
    }

    public function get_course_progress($id){

    }
}
