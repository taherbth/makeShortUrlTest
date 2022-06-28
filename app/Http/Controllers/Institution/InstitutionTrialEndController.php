<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Http\Requests\InstitutionEnableDisableUsersRequest;
use App\Models\Authority;
use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use Illuminate\Http\Request;

class InstitutionTrialEndController extends Controller
{
    protected $stripe,$authority, $CacheController,$bill_estimation_cache_type,$payment_method_list_cache_type;
    public function __construct(CacheController $CacheController){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $this->authority=Authority::first();
        $this->CacheController=$CacheController;
        $this->bill_estimation_cache_type='bill_estimation';
        $this->payment_method_list_cache_type='payment_methods_list';
    }

    public function get_package_pricing(){

        $unit_amount = $this->stripe->prices->retrieve(
            $this->authority->stripe_price_id,
            []
        )["unit_amount"];

        if($this->authority->stripe_tax_id != null){
            $tax_rate = $this->stripe->taxRates->retrieve(
            $this->authority->stripe_tax_id,
            []
            )["percentage"];
        }else{
            $tax_rate=0;
        }

        return response([
            'unit_amount'=>$unit_amount,
            'tax_rate'=>$tax_rate,
        ]);
    }

    public function post_enable_disable_users(InstitutionEnableDisableUsersRequest $request){
        $institution=Institution::find(Institution::return_id());
        if($request->check_all_users==true){
            $enable_facilitators=[];
            $enable_students=[];
            //if(!empty($request->disabled_user_list)){
            foreach ($request->disabled_user_list as $disabled_user){
                if($disabled_user["member_type"] == 1){
                    $facilitator= Facilitator::find($disabled_user["id"]);
                    $facilitator->status=0;
                    $enable_facilitators[]=$facilitator->id;
                    $facilitator->save();
                }
                else if($disabled_user["member_type"] == 2){
                    $student= Student::find($disabled_user["id"]);
                    $student->status=0;
                    $enable_students[]=$student->id;
                    $student->save();
                }
            }

            $facilitator_list_for_activate=Facilitator::whereNotIn('id',$enable_facilitators)->get();
            $student_list_for_activate=Student::whereNotIn('id',$enable_students)->get();

            foreach ($facilitator_list_for_activate as $enable_facilitator){
                $facilitator=Facilitator::find($enable_facilitator->id);
                $facilitator->status=1;
                $facilitator->save();
            }

            foreach ($student_list_for_activate as $enable_student){
                $student=Student::find($enable_student->id);
                $student->status=1;
                $student->save();
            }

        }
        else if($request->check_all_users==false){
            $disable_facilitators=[];
            $disable_students=[];
            foreach ($request->enable_users_list as $enabled_user){
                if($enabled_user["member_type"] == 1){
                    $facilitator= Facilitator::find($enabled_user["id"]);
                    $facilitator->status=1;
                    $disable_facilitators[]=$facilitator->id;
                    $facilitator->save();
                }
                else if($enabled_user["member_type"] == 2){
                    $student= Student::find($enabled_user["id"]);
                    $student->status=1;
                    $disable_students[]=$student->id;
                    $student->save();
                }
            }

            $facilitator_list_for_deactivate=Facilitator::whereNotIn('id',$disable_facilitators)->get();
            $student_list_for_deactivate=Student::whereNotIn('id',$disable_students)->get();
            foreach ($facilitator_list_for_deactivate as $disable_facilitator){
                $facilitator=Facilitator::find($disable_facilitator->id);
                $facilitator->status=0;
                $facilitator->save();
            }

            foreach ($student_list_for_deactivate as $disable_student){
                $student=Student::find($disable_student->id);
                $student->status=0;
                $student->save();
            }
        }
        $this->CacheController->forget_cache($institution->id,$institution->email, $this->payment_method_list_cache_type);
        $this->CacheController->forget_cache($institution->id,$institution->email, $this->bill_estimation_cache_type);
        return response(['message'=>'User Updated']);
    }
}
