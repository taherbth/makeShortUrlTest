<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditProfileRequest;
use App\Jobs\StudentProfilePicChange;
use App\Models\Institution;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentApiProfileController extends Controller
{
    public function get_profile(){
        $user=Student::where('id',Student::return_id())->first(['name','email','profile_picture','is_mailable','profile_picture','institution_id','created_at']);
        $institution=Institution::find($user->institution_id);
        $user->institution_name=$institution->institution_name;
        $user->institution_code=$institution->institution_code;
        $user->app_domain=env('APP_URL');
        return $user;
    }

    public function edit_profile(EditProfileRequest $request){
        $student=Student::find(Student::return_id());

        if($request->has('name')) {
            $student->name = $request->name;
        }

        if($request->has('avatar')){
//            $student_dp_change_job = new StudentProfilePicChange($request->file('avatar'), $request->user()->id);
//            dispatch($student_dp_change_job);
        $path = $request->file('avatar')->storeAs(
            '',
            $request->user()->id.'.jpg',
            's3_student_profile_pictures'
        );

        $student->profile_picture = Storage::disk('s3_student_profile_pictures')->url($request->user()->id.'.jpg');
        }

        $student->save();

        return response(['message'=>'Profile Edit Successful', 'pic'=> $student->profile_picture]);
    }
}
