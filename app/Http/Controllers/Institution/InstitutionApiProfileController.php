<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CourseController;
use App\Http\Controllers\Helper\OTPController;
use App\Http\Requests\EditInstitutionProfileRequest;
use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\InstitutionGetAllUserRequest;
use App\Http\Requests\InstitutionNewUserInvitationRequest;
use App\Http\Requests\InstitutionUserApprovalRequest;
use App\Http\Requests\InstitutionUserDeactivationRequest;
use App\Jobs\InstitutionAdminProfilePicChange;
use App\Jobs\InstitutionProfilePicChange;
use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InstitutionApiProfileController extends Controller
{
    public function increment_code($code){

        $last_number=0;
        if(Institution::where('institution_code',$code)->exists()){
            //return 1;
            $last_digit=substr($code, -1);
            if(is_numeric($last_digit)) {
                //return 1;
                $last_digit++;
                $code=substr($code, 0,-1).$last_digit;
                return $this->increment_code($code);
            }
            else{
                $last_number++;
                $code=$code.$last_number;
                return $this->increment_code($code);
            }
        }
        else
            return $code;
    }

    public function get_admin_profile(){
        $user=Institution::where('id',Institution::return_id())->first([
            'admin_name',
            'admin_profile_picture',
            'institution_name',
            'institution_code',
            'created_at'
        ]);

        $user->number_of_students = $user->student_s()->count();
        $user->number_of_facilitators = $user->facillitor_s()->count();
        return $user;

    }

    public function get_institution_profile(){
        $user=Institution::where('id',Institution::return_id())->first([
            'institution_name',
            'institution_address',
            'created_at',
            'established_at',
            'institution_profile_picture'
        ]);

        $user->number_of_students = $user->student_s()->count();
        $user->number_of_facilitators = $user->facillitor_s()->count();
        return $user;

    }

    public function get_user_approval_status(InstitutionUserApprovalRequest $request): int
    {
        $institution=Institution::find(Institution::return_id());

        if($request->has('user_approval') && $request->user_approval == !$institution->user_approval){
            $institution->user_approval = !$institution->user_approval;
            $institution->save();
        }

        return (int)$institution->user_approval;

    }

    public function edit_admin_profile(EditProfileRequest $request){
        $institution=Institution::find(Institution::return_id());

        if($request->has('avatar')){
//            $institution_admin_dp_change_job = new InstitutionAdminProfilePicChange($request->file('avatar'), $request->user()->id);
//            dispatch($institution_admin_dp_change_job);
            $path = $request->file('avatar')->storeAs(
                '',
                $request->user()->id.'.jpg',
                's3_institution_admin_profile_pictures'
            );
            $institution->admin_profile_picture = Storage::disk('s3_institution_admin_profile_pictures')->url($request->user()->id.'.jpg');
        }


        if($request->has('name')){
            $institution->admin_name = $request->name;
        }

        $institution->save();
        return response(['message'=>'Profile Edited Successfully','pic'=>$institution->admin_profile_picture]);
    }

    public function edit_institution_profile(EditInstitutionProfileRequest $request){
        $institution=Institution::find(Institution::return_id());
        if($request->has('avatar')){
//            $institution_dp_change_job = new InstitutionProfilePicChange($request->file('avatar'), $request->user()->id);
//            dispatch($institution_dp_change_job);
            $path = $request->file('avatar')->storeAs(
                '',
                $request->user()->id.'.jpg',
                's3_institution_profile_pictures'
            );
            $institution->institution_profile_picture = Storage::disk('s3_institution_profile_pictures')->url($request->user()->id.'.jpg');
        }

        if($request->has('institution_name')){
            $words = explode(" ", $request['institution_name']);
            $code = "";

            foreach ($words as $w) {
                $code .= $w[0];
            }
            //return $code;
            $code=$this->increment_code(strtolower($code));

            $institution->institution_name = $request->institution_name;
            $institution->institution_code = $code;
            if($institution->stripe_id != null){
                $stripe = new \Stripe\StripeClient(
                    env('STRIPE_SECRET')
                );
                $stripe->customers->update(
                    $institution->stripe_id,
                    ['name' => $request->institution_name]
                );
            }
        }
        if($request->has('institution_address')){
            $institution->institution_address = $request->institution_address;
        }
        if($request->has('established_at')){
            $institution->established_at = $request->established_at;
        }

        $institution->save();

        return response(['message'=>'Profile Edited Successfully','pic'=>$institution->institution_profile_picture]);
    }



}
