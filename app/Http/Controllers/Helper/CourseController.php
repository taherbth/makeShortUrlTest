<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Facilitator;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public $facilitator_image='App\Models\Facilitator';
    public $facilitator_json_image="App\Models\Facilitator";
    public $institution_image='App\Models\Institution';
    public $authority_image='App\Models\Authority';
    public $institution_json_image="App\Models\Institution";

    public function return_facilitator_course(){
        return Course::where([
            ['publishable_id', Facilitator::return_id()],
            ['publishable_type', $this->facilitator_image]
        ]);
    }

    public function get_course($id){
        $course= Course::find($id);
        if(!isset($course)) return response(['message'=>'Error, Course Not Found'],404);
        return $course;
    }

    public function facilitator_get_course(){
        $auth_facilitator_id=Auth::guard('api-facilitator')->user()->id;
        $courses= Institution::find(Facilitator::find($auth_facilitator_id)->institution_id)->course_s()->where('upload_status',1)->get();
//        foreach ($courses as $course){
//            if($course->facilitator_id!=$auth_facilitator_id && ($course->is_public == 0 && $course->is_draft == 0 )){
//                $courses->forget($course) ;
//            }
//        }
        if(!isset($courses)) return response(['message'=>'Error, Course Not Found'],404);
        return $courses;
    }

    public function student_verify_single_course($id,$all_active_courses){
        $active_course_id=[];
        foreach ($all_active_courses as $active_course){
            $active_course_id[]= $active_course->id;
        }
        if(!in_array($id,$active_course_id))
            return 0;
        else return 1;

    }

    public function validate_facilitator($course_id){
        $course=Course::find($course_id);
        if($course->publishable_type == $this->facilitator_json_image && $course->publishable_id != Facilitator::return_id())
            return 0;

        else if($course->publishable_type == $this->institution_json_image && $course->publishable_id != Facilitator::find(Facilitator::return_id())->institution_id)
            return 0;

        else
            return 1;
         // Validate Course
    }

    public function validate_institution($course_id){
        $course=Course::find($course_id);
        if($course->publishable_type == $this->facilitator_json_image && in_array($course->publishable_id,Institution::return_facilitators_id_array()))
            return 0;

        else if($course->publishable_type == $this->institution_json_image && $course->publishable_id != Institution::return_id())
            return 0;

        else
            return 1;
        // Validate Course
    }

    public function paginate($items, $perPage = 15, $page = null, $options = []): LengthAwarePaginator
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function get_course_publisher($id): string
    {
        $ab= \App\Models\Course::find($id)->publishable_type;
        $strArray = explode("\\",$ab);
        return end($strArray);
    }
}
