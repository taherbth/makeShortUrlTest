<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\CookieController;
use App\Http\Controllers\Helper\CourseController;
use App\Http\Controllers\Helper\EncryptionController;
use App\Http\Requests\VideoInfoUploadRequest;
use App\Http\Requests\VideoUploadRequest;
use App\Http\Requests\UploadThumbnailRequest;
use App\Jobs\RemoveVideo;
use App\Jobs\UploadVideo;
use App\Models\Course;
use App\Models\Facilitator;
use App\Models\Student;
use App\Notifications\Facilitator\FacilitatorNewCoursePublished;
use App\Notifications\Student\StudentNewCourseUploadNotification;
use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;
use FFMpeg;


use Symfony\Component\Process\Process;
use function PHPUnit\Framework\isEmpty;

class FacilitatorApiUploadsController extends Controller
{

    protected $CookieController;
    protected $EncryptionController;
    protected $CourseController;
    protected $CacheController;
    protected $facilitator;
    protected $cache_type_facilitator_get_students;

    public function __construct(CookieController $CookieController, EncryptionController $EncryptionController, CourseController $CourseController, CacheController $CacheController){
        $this->CookieController = $CookieController;
        $this->EncryptionController = $EncryptionController;
        $this->CourseController = $CourseController;
        $this->CacheController = $CacheController;
        $this->facilitator = Auth::guard('api-facilitator')->user();
        $this->cache_type_facilitator_get_students='facilitator_get_students';
    } // Get Cookie Controller Controller Properties

    public function get_students(){
        $cache=$this->CacheController->get_cache($this->facilitator->id,$this->facilitator->email,$this->cache_type_facilitator_get_students);
        if($cache != null)
            return $cache;

        $students= Student::where('institution_id',$this->facilitator->institution_id)->get(['name','id']);
        if(isEmpty($students)){
            $this->CacheController->make_cache($this->facilitator->id,$this->facilitator->email,$students,$this->cache_type_facilitator_get_students);
            return $students; // return all students of same institution
        }
        return response(['message'=>'No Students found'],404);
    }

    public function upload_video(VideoUploadRequest $request)
    {
        $this->CookieController->flush_cookies_except();


        if (!$request->hasFile('video')) {
            return response(['message' => 'Please provide a video file'], 500);
        }


        try {
            $video=Facilitator::find(Facilitator::return_id())->course_f()->create([
                'video_original_name' => $request->video->getClientOriginalName(),
            ]);
            $video_path= storage_path().'/videos/raw/'.$video->id.'.mp4';
            if (!env('AWS_ENV'))
                $video_path=str_replace('\\','/',$video_path);

            $d2= (env('AWS_ENV')) ? Storage::disk('course_videos_raw') : Storage::disk('course_videos_raw_local');
            $d2->put($video->id.'.mp4', fopen($request->file('video'), 'r+'));
            //$request->file('video')->storeAs('', $video->id.'.mp4', 'course_videos_raw_local');

            if (env('AWS_ENV')){
                $this->dispatch(new UploadVideo($video_path,$video->id));
                $video->update([
                    'path' => Storage::disk('s3_course_videos')->url($video->id.'.mp4')
                ]);
            }

            else{

                $video->update([
                    'path' => Storage::disk('course_videos_raw_local')->url($video->id.'.mp4')
                ]);
            }

        } catch (\Exception $e) {
            $this->CookieController->flush_cookies_except();
            return response(['message' => 'Error Occurred, can not create course'], 500);
        }

        $thumbnail_path = public_path().'/thumbnails/'. $video->id.'/';
        if (!env('AWS_ENV'))
            $thumbnail_path=str_replace('\\','/',$thumbnail_path);

//        shell_exec("mkdir '".$thumbnail_path."'");
        mkdir($thumbnail_path);
        //set folder for thumbnail


        //set folder for raw video

        if (env('AWS_ENV')){
            $ffmpeg = FFMpeg\FFProbe::create(array(
                'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe',
                'timeout'          => 3600, // The timeout for the underlying process
                'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
            ), );
        }
        else{
            $ffmpeg = FFMpeg\FFProbe::create(array(
                'ffmpeg.binaries'  => env('FFMPEG_PATH'),
                'ffprobe.binaries' => env('FFPROBE_PATH'),
                'timeout'          => 3600, // The timeout for the underlying process
                'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
            ), );
        }

        $duration =$ffmpeg
            ->format($video_path) // extracts file informations
            ->get('duration');

        $duration =intval($duration);
        $video_path1= request()->video;
        $thumbnail_image = $video->id . "-" . number_format((float)($duration / 3), 2, '.', '') . ".jpg";
        $thumbnail_image1 = $video->id . "-" . number_format((float)($duration / 2), 2, '.', '') . ".jpg";
        $thumbnail_image2 = $video->id . "-" . number_format((float)((3 * $duration) / 2), 2, '.', '') . ".jpg";
        // Set thumbnail name

        try {

            (new \Lakshmaji\Thumbnail\Thumbnail)
                ->getThumbnail($video_path1, $thumbnail_path, $thumbnail_image, $duration / 3);
            (new \Lakshmaji\Thumbnail\Thumbnail)
                ->getThumbnail($video_path1, $thumbnail_path, $thumbnail_image1, $duration / 2);
            (new \Lakshmaji\Thumbnail\Thumbnail)
                ->getThumbnail($video_path1, $thumbnail_path, $thumbnail_image2, (2 * $duration) / 3);

        } catch (\Exception $e) {
            $this->CookieController->flush_cookies_except();
            return response(['message' => 'Error Occurred, can not generate thumbnail'], 500);
        }
//         Generate Thumbnail


        Course::where('id', $video->id)->update(
            array(
        "durationInSec" =>$duration
            ));
        //Storing Duration in seconds

        $thumbnail_files = array_diff(scandir(

            $thumbnail_path,),
            array('.', '..')
        );
        $this->dispatch(new RemoveVideo($video_path));

        setcookie('course',$this->EncryptionController->encryption($video->id ,'encrypt' ),time() + (600));
        return response([
            'course_id'=>$video->id,
            'course_thumbnails'=>$thumbnail_files,
        ]);
    }

    public function upload_video_info(VideoInfoUploadRequest $request)
    {
        $pvt_course_std_id=[];

        if($request->is_public == 1 && $request->is_draft == 1)
            return response(['message'=>'Drafted Course Can not be public'],500);
        // Validating Input

        if(!isset ($_COOKIE['course']))
            return response(['message'=>'Please Upload the course video first'],500);
        //Checking Cookie

        try {
            $id= $this->EncryptionController->encryption($_COOKIE['course'],'decrypt');
            $course = Course::where('id', $id)->first();
        } catch (\Exception $e) {
            return response(['message' => 'Please upload the video first'], 500);
        } //Check if video file exists

        if ((Facilitator::return_id() != $course->publishable_id) || ($course->publishable_type != $this->CourseController->facilitator_image)) {
            return response(['message' => 'Something wrong, please login again'], 500);
        } //Check if this is the right user


//        try{
//            Image::make($request->thumbnail)
//                ->resize(960, 565)
//                ->encode('jpg', 50)
//                ->save($request->thumbnail);
//
//        }catch(\Exception $e){
//            return response(['message'=>'Can not format thumbnail'],500);
//        }


        try {
            Course::where('id', $id)->update(
                array(
                    "title" => $request->title,
                    "details" => $request->details,
                    "thumbnail" => $request->thumbnail,
                    "topic" => $request->topic,
                    "chapter" => $request->chapter,
                    "episode" => $request->episode,
                    "is_draft"=>$request->is_draft,
                    "is_public"=>$request->is_public,
                )
            );
            //Storing Course Info
        }
        catch (QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response(['message' => 'Error Occurred, This Episode of this Chapter in this Course Already Exists'], 500);
            }
        } catch (\Exception $e) {
            return response(['message' => 'Error Occurred, can not store course details'], 500);
        }


        $questions = array_keys($request->questions);
        if(isset($request->answer_option)) $answer_options = array_keys($request->answer_option);
        try{
            foreach ($questions as $question) {

                if (isset($request->answer_placeholder[$question]) && isset($request->answer_length[$question])){

                    $course_question_option=$course->course_question_c()->create([
                        'question' => $request->questions[$question],
                        'question_type' => $request->question_type[$question],
                        'answer_placeholder' => $request->answer_placeholder[$question],
                        'answer_length' => $request->answer_length[$question],
                    ]);
                }
                else if (isset($request->answer_placeholder[$question]) && isset($request->answer_min_length[$question]) &&  $request->answer_max_length[$question]){

                    $course_question_option=$course->course_question_c()->create([
                        'question' => $request->questions[$question],
                        'question_type' => $request->question_type[$question],
                        'answer_placeholder' => $request->answer_placeholder[$question],
                        'answer_min_length' => $request->answer_min_length[$question],
                        'answer_max_length' => $request->answer_max_length[$question],
                    ]);
                }

                else{
                    $course_question_option=$course->course_question_c()->create([
                        'question' => $request->questions[$question],
                        'question_type' => $request->question_type[$question],
                    ]);
                }

                if(isset($answer_options)) {
                    if (in_array($question, $answer_options)) {
                        $options = array_keys($request->answer_option[$question]);
                        foreach ($options as $option) {
//                            if (isset($options[$option - 1]) && $request->answer[$question] == $options[$option - 1]) {
//                                $course_question_option->course_question_option_cq()->create([
//                                    'courseQuestion_option' => $request->answer_option[$question][$option],
//                                    'is_answer' => '1'
//                                ]);
//                            } else {
                                $course_question_option->course_question_option_cq()->create([
                                    'courseQuestion_option' => $request->answer_option[$question][$option],
                                ]);
//                            }
                        }
                    }
                }
            }
        }
        catch(\Exception $e){
            return response(['message' => 'Error Occurred, can not store course questions'], 500);
        }  // Storing Course Questions

        if ($request->is_public == 0 && $request->is_draft == 0 && $request->has('students') && $request->students != null){
            try{
                foreach ($request->students as $request_student){
                    $int_student= (array_map('intval', explode(',', $request_student)));
                    $pvt_course_std_id[]=$int_student;
                    $student= Student::find($int_student)->first();
                    if ($student->institution_id != Facilitator::find(Facilitator::return_id())->institution_id){
                        return response(['message'=>'Error, Students Name are incorrect'],500);
                    }
                    $student->course_c()->attach($course->id);
                }
            }
            catch(QueryException $ex){
                //return response($ex->getMessage(),500);
                return response(['message'=>'Error, Some students Is already given permission to this course'],500);
                // Note any method of class PDOException can be called on $ex.
            }
            catch (\Exception $e){
                return response(['message'=>'Error, can not store students'],500);
            }
        } // assign students if private course

        $this->CookieController->flush_cookies_except();
        //Flush All Cookies

        Course::where('id',$id)->update(array("upload_status"=>1));

        if ($request->is_public == 1 && $request->is_draft == 0 && (!$request->has('students') || $request->students = null)){
             try{
                 $pvt_students=Student::where('institution_id',Facilitator::find(Auth::guard('api-facilitator')->user()->id)->institution_id)->get();
                 Notification::send($pvt_students ,(new StudentNewCourseUploadNotification($id)));
            } catch(\Exception $e){
                return response(['message'=>'Error Notifying Students'],500);
            }
        }

        else if ($request->is_public == 0 && $request->is_draft == 0 && $request->has('students') && $request->students = !null && sizeof($pvt_course_std_id)>0 ){
            try{
            $pvt_students = Student::whereIn('id',$pvt_course_std_id)->get();
            Notification::send($pvt_students ,(new StudentNewCourseUploadNotification($id)));
            } catch(\Exception $e){
            return response(['message'=>'Error Notifying Students'],500);
            }
        }

        $this->CacheController->forget_cache($this->facilitator->id,$this->facilitator->email,'facilitator_last_published_video');
        $this->CacheController->forget_cache($this->facilitator->id,$this->facilitator->email,'facilitator_get_topics');
        $this->CacheController->forget_pagination_cache($this->facilitator->id,$this->facilitator->email,'facilitator_get_courses',1);
        $this->CacheController->forget_pagination_cache($this->facilitator->id,$this->facilitator->email,'facilitator_get_course_published',1);
        $this->CacheController->forget_pagination_cache($this->facilitator->id,$this->facilitator->email,'facilitator_get_course_drafted',1);

        return response(['message'=>'All info Updated Successfully']);
    }

    public function upload_thumbnails(UploadThumbnailRequest $request){
        if(!isset ($_COOKIE['course'])){
            return response(['message'=>'Please Upload the course video first'],500);
        }

        try{
            $video= $this->EncryptionController->encryption($_COOKIE['course'],'decrypt');

            $thumbnail_path = '/var/www/login/public/thumbnails/' . $video.'/';
//            $request->file('thumbnail')->
//            storeAs($video, request()->file('thumbnail')
//                ->getClientOriginalName(), 'course_videos_thumbnails');
            //Storing Thumbnail File
            $request->file('thumbnail')->storeAs($video.'/', Carbon::now()->timestamp.'.jpg', 'course_videos_thumbnails');

            $files = array_diff(scandir(
                Storage::disk('course_videos_thumbnails')
                    ->getAdapter()->getPathPrefix() . $video),
                array('.', '..')
            );   // get all thumbnail files

            foreach ($files as $file){
                $array[] = str_replace('\\', '/', Storage::disk('course_videos_thumbnails')
                        ->getAdapter()->getPathPrefix() . $video.'\\'. $file);
            }  // attaching full location to thumbnails
            return response(['thumbnails'=>$array , 'video_id'=>$video]);

        } Catch(\Exception $e){
            return response(['message'=>'Can not store Your thumbnail, please select a new one'],500);
        }
    }

    public function get_thumbnails(){

        if(!isset ($_COOKIE['course']))
            return response(['message'=>'Please Upload the course video first'],500);
        //Checking Cookie

        try {
            $course_id= $this->EncryptionController->encryption($_COOKIE['course'],'decrypt');
            $course = Course::where('id', $course_id)->first();
        } catch (\Exception $e) {
            return response(['message' => 'Please upload the video first'], 500);
        } //Check if video file exists

        $thumbnail_path = Storage::disk('course_videos_thumbnails')
                ->getAdapter()->getPathPrefix() . $course_id.'\\';
        $thumbnail_path =str_replace('\\', '/', $thumbnail_path);

        $course_videos_playlist_files_raw = array_diff(scandir(
            Storage::disk('course_videos_thumbnails')
                ->getAdapter()->getPathPrefix() . $course_id),
            array('.', '..', '.ts')
        );
        // Getting playlist files

        $course_videos_playlist_files=preg_grep("/^(\.|\.\.)$|\.ts$/",
            $course_videos_playlist_files_raw, PREG_GREP_INVERT);
        // filtering out .ts files
        $array=[];
        foreach($course_videos_playlist_files as $a){
            $array[]=$thumbnail_path.$a;
        }
        return response(['video_id'=> $course_id, 'thumbnails'=>$array]);

    }

    public function get_course_drafted(){
        $cache_type_facilitator_get_course_drafted = 'facilitator_get_course_drafted';
        $cache= $this->CacheController->get_pagination_cache($this->facilitator->id,$this->facilitator->email,request()->page,$cache_type_facilitator_get_course_drafted);
        if ($cache != null){
            $trimmed_cache=$this->CacheController->trim_cache($cache);
            return response($trimmed_cache);
        }
        try{
            $courses_drafted= Facilitator::return_facilitator_uploaded_courses()->where([['is_draft',1],['is_public',0]])->orderByDesc('id')->get();
        }catch(\Exception $e){
            return response(['message'=>'Error, Can not retrieve your drafts '],404);
        } //Check  if Drafts Exists

        foreach($courses_drafted as $data) {
            if ($data->updated_at == null) {
                $data->uploaded_hours_ago = NOW()->diffInHours($data->created_at);
            } else {
                $data->uploaded_hours_ago = NOW()->diffInHours($data->updated_at);
            }
            $data->uploaded_days_ago=intval($data->uploaded_hours_ago/24);
            //Generating UploadedHoursAgo field

            $data->course_duration = sprintf('%02d:%02d:%02d', ($data->durationInSec / 3600), ($data->durationInSec / 60 % 60), $data->durationInSec % 60);
        }
        $courses_drafted= $this->CourseController->paginate($courses_drafted, $perPage = 7, $page = null, $options = []);
        $this->CacheController->make_pagination_cache(
            $this->facilitator->id,
            $this->facilitator->email,
            $courses_drafted,
            json_decode(json_encode($courses_drafted))->current_page,
            $cache_type_facilitator_get_course_drafted
        );
        return $courses_drafted;
    }

    public function get_course_published(){
        $cache_type_facilitator_get_course_published = 'facilitator_get_course_published';

        $cache= $this->CacheController->get_pagination_cache($this->facilitator->id,$this->facilitator->email,request()->page,$cache_type_facilitator_get_course_published);
        if ($cache != null){
            $trimmed_cache=$this->CacheController->trim_cache($cache);
            return response($trimmed_cache);
        }
        try{
            $courses_published= Facilitator::return_facilitator_uploaded_courses()->where([['is_draft',0],['is_public',1]])->orderByDesc('id')->get();
        }catch(\Exception $e){
            return response(['message'=>'Error, Can not retrieve your published courses'],404);
        } //Check  if Drafts Exists
        foreach($courses_published as $data) {
            if ($data->updated_at == null) {
                $data->uploaded_hours_ago = NOW()->diffInHours($data->created_at);
            } else {
                $data->uploaded_hours_ago = NOW()->diffInHours($data->updated_at);
            }
            $data->uploaded_days_ago=intval($data->uploaded_hours_ago/24);
            //Generating UploadedHoursAgo field
            $data->viewes= $data->watchHistory_c()->count();
            $data->course_duration = sprintf('%02d:%02d:%02d', ($data->durationInSec / 3600), ($data->durationInSec / 60 % 60), $data->durationInSec % 60);
        }
        $courses_published= $this->CourseController->paginate($courses_published, $perPage = 7, $page = null, $options = []);
        $this->CacheController->make_pagination_cache(
            $this->facilitator->id,
            $this->facilitator->email,
            $courses_published,
            json_decode(json_encode($courses_published))->current_page,
            $cache_type_facilitator_get_course_published
        );
        return $courses_published;
    }
}
