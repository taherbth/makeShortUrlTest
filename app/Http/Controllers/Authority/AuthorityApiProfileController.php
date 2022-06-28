<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditProfileRequest;
use App\Jobs\AuthorityProfilePicChange;
use App\Models\Authority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorityApiProfileController extends Controller
{
    public function get_profile(){
        $user=Authority::where('id',Authority::return_id())->first(['name','email','profile_picture','created_at']);
        return $user;
    }

    public function edit_profile(EditProfileRequest $request){
        $authority=Authority::find(Authority::return_id());

        if($request->has('name')) {
            $authority->name = $request->name;
        }

        if($request->has('avatar')){
//            $authority_dp_change_job = new AuthorityProfilePicChange($request->file('avatar'), $request->user()->id);
//            dispatch($authority_dp_change_job);
            $path = $request->file('avatar')->storeAs(
                '',
                $request->user()->id.'.jpg',
                's3_authority_profile_pictures'
            );

            $authority->profile_picture =Storage::disk('s3_authority_profile_pictures')->url($request->user()->id.'.jpg');
        }


        $authority->save();

        return response(['message'=>'Profile Edit Successful','pic'=>$authority->profile_picture]);
    }
}
