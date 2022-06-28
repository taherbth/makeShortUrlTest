<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Institution;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthorityApiInstitutionController extends Controller
{
    public function deactivate_institution($id){
        $institution=Institution::find($id);

        if(!$institution->exists())
            return response(['message'=>'Institution not found'],500);

        if($institution->status==0)
            return response(['message'=>'User Is Already Deactivated'],500);

        $institution->status=0;
        $institution->save();

        return response(['message'=>'Successfully Institution Deactivated']);
    }

    public function reactivate_institution($id){
        $institution=Institution::find($id);

        if(!$institution->exists())
            return response(['message'=>'Institution not found'],500);

        if($institution->status==1)
            return response(['message'=>'User Is Already Activated'],500);

        $institution->status=1;
        $institution->save();

        return response(['message'=>'Successfully Institution Reactivated']);
    }

    public function get_institution_details($id){

        $institution=Institution::find($id);

        if(!$institution->exists())
            return response(['message'=>'Institution not found'],500);

        try{
            $billing=$institution->billing_address_i()->where('is_default',1)->first([
                'country',
                'zip_code',
                'state',
                'city',
                'address_line_1',
                'address_line_2',
                'email'
            ]);

            $billing->country = Country::find($billing->country)->name;
            $billing->state = State::find($billing->state)->name;
            $billing->city = City::find($billing->city)->name;
        }catch(\Exception $e){
            $billing = null;
        }
        //return Carbon::now()->timestamp;

         $last_payment=$institution
            ->invoice_s()
            ->where([
                ['status','active'],
                ['cancel_at','>',Carbon::now()->timestamp]
            ])->orderByDesc('id')
            ->first('cancel_at');
        //return Carbon::createFromTimestamp($last_payment)->toDateTimeString();
        if($last_payment== null)
            $last_payment_cancel_at=null;
        else
            $last_payment_cancel_at=$last_payment->cancel_at;

        $institution_facilitator_count=$institution->facillitor_s()->count();
        $institution_student_count=$institution->student_s()->count();

        return response([
            'institution_details'=>$institution,
            'billing details'=>$billing,
            'institution_facilitator_count'=>$institution_facilitator_count,
            'institution_student_count'=>$institution_student_count,
            'last_payment'=>$last_payment_cancel_at,
            'url'=>env('APP_URL'),
        ]);
    }
}
