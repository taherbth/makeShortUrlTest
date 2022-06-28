<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditProfileRequest;
use App\Jobs\FacilitatorProfilePicChange;
use App\Models\Facilitator;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacilitatorApiProfileController extends Controller
{
    public function get_profile(){
        $user=Facilitator::where('id',Facilitator::return_id())->first(['name','email','profile_picture','is_mailable','profile_picture','institution_id','created_at']);
        $institution=Institution::find($user->institution_id);
        $user->institution_name=$institution->institution_name;
        $user->institution_code=$institution->institution_code;
        $user->app_domain=env('APP_URL');
        return $user;
    }

    public function edit_profile(EditProfileRequest $request){
        $facilitator=Facilitator::find(Facilitator::return_id());

        if($request->has('name')) {
            $facilitator->name = $request->name;
        }

        if($request->has('avatar')){
//            $facilitator_dp_change_job = new FacilitatorProfilePicChange($request->file('avatar'), $request->user()->id);
//            dispatch($facilitator_dp_change_job);
             $path = $request->file('avatar')->storeAs(
                 '',
                 $request->user()->id.'.jpg',
                 's3_facilitator_profile_pictures'
             );



             $facilitator->profile_picture = Storage::disk('s3_facilitator_profile_pictures')->url($request->user()->id.'.jpg');
        }

        $facilitator->save();

        return response(['message'=>'Profile Edit Successful','pic'=>$facilitator->profile_picture]);
    }
}
