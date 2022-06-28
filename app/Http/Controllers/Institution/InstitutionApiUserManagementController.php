<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CourseController;
use App\Http\Controllers\Helper\OTPController;
use App\Http\Requests\InstitutionGetAllUserRequest;
use App\Http\Requests\InstitutionNewUserInvitationRequest;
use App\Http\Requests\InstitutionUserDeactivationRequest;
use App\Mail\Facilitator\Invitation\NewFacilitatorInvitationMail;
use App\Mail\Student\Invitation\NewStudentInvitationMail;
use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class InstitutionApiUserManagementController extends Controller
{
    protected $CourseController, $OTPController;
    public function __construct(CourseController $CourseController, OTPController $OTPController){
        $this->CourseController = $CourseController;
        $this->OTPController = $OTPController;
    } // Get Cookie Controller Controller Properties


    public function get_all_users(InstitutionGetAllUserRequest $request)//: \Illuminate\Pagination\LengthAwarePaginator
    {
        $all_users=new Collection();
        $string= $request->toArray()["query"];

        ($request->is_active == null) ? $is_active=[0,1]
            :$is_active=[intval($request->is_active)];

        $students=Student::where('institution_id',Institution::return_id())
            ->whereIn('status',$is_active)
            ->search($string)
            ->get(['id','name','email','profile_picture','created_at','status']);

        foreach ($students as $student){
            $student->member_type=2;
            $all_users->push($student);
        }

        $facilitators=Facilitator::where('institution_id',Institution::return_id())
            ->whereIn('status',$is_active)
            ->search($string)
            ->get(['id','name','email','profile_picture','created_at','status']);

        foreach ($facilitators as $facilitator){
            $facilitator->member_type=1;
            $all_users->push($facilitator);
        }

        $all_users=$all_users->sortBy('name');
        $count= $all_users->count();
        $all_users= $this->CourseController->paginate($all_users, $perPage = 10, $page = null, $options = []);

        return response ([
            'all_users'=>$all_users,
            'count'=>$count
            ]);
    }

    public function post_deactivate_user(InstitutionUserDeactivationRequest $request){

        if($request->member_type==1){
            $facilitator=Facilitator::find($request->user_id);

            if($facilitator==null)
                return response(['message'=>'Facilitator not found'],500);

            if($facilitator->institution_id != Institution::return_id())
                return response(['message'=>'This Facilitator is not in your Institution'],500);

            if($facilitator->status != $request->status){
                $facilitator->status=$request->status;
                $facilitator->save();
            }
            else{
                return ($request->status == 1) ?  response(['message'=>'Facilitator Is Already Activated'],500)
                    :  response(['message'=>'Facilitator Is Already Deactivated'],500);
            }
        }

        else if($request->member_type==2){
            $student=Student::find($request->user_id);

            if($student==null)
                return response(['message'=>'Student not found'],500);

            if($student->institution_id != Institution::return_id())
                return response(['message'=>'This Student is not in your Institution'],500);

            if($student->status != $request->status){
                $student->status=$request->status;
                $student->save();
            }
            else{
                return ($request->status == 1) ?  response(['message'=>'Student Is Already Activated'],500)
                    :  response(['message'=>'Student Is Already Deactivated'],500);
            }
        }

        return response(['message'=>'User Status changed Successfully']);
    }

    public function post_invite_users(InstitutionNewUserInvitationRequest $request){
        $invited_emails= ['emails'=>(array_map('strval',str_replace(' ', '', explode(',', $request->email))))];
        $invited_emails['emails']= array_filter($invited_emails['emails']);
        $validator =Validator::make($invited_emails,[
            'emails.*'=>'required|email|unique:facilitators,email|unique:students,email|unique:institutions,email'
        ]);

        if ($validator->fails()) {
            return response(['message'=>'Error, Invited used is already a member of WAM'],500);
        }


        if($request->role == 1 ){
//            foreach($invited_emails['emails'] as $email){
//                Mail::to($email)->queue(new NewFacilitatorInvitationMail(Institution::return_id()));
//            }
            $this->OTPController->FacilitatorInvitationSend($invited_emails['emails'],Institution::return_id());
        }


        else if($request->role == 2){
//            foreach ($invited_emails['emails'] as $email){
//                Mail::to($email)->queue(new NewStudentInvitationMail(Institution::return_id()));
//            }
            $this->OTPController->StudentInvitationSend($invited_emails['emails'],Institution::return_id());
        }

        else{
            return response(['message'=>'Error, Please Provide a role'],500);
        }

        return response(['message'=>'Invitation Send Successfully']);

    }
}
