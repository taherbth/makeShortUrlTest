<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\EncryptionController;
use App\Http\Controllers\Institution\InstitutionApiPaymentController;
use App\Models\Authority;
use App\Models\Institution;
use App\Models\Student;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class CheckIfInstitutionOfStudentSubscribed
{

    protected $stripe,$CacheController,$EncryptionController ,$InstitutionApiPaymentController;
    public function __construct(CacheController $CacheController, EncryptionController $EncryptionController, InstitutionApiPaymentController $InstitutionApiPaymentController){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $this->CacheController=$CacheController;
        $this->EncryptionController=$EncryptionController;
        $this->InstitutionApiPaymentController=$InstitutionApiPaymentController;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $student=Student::find(Student::return_id());
        $cache=$this->CacheController->get_subscription_cache($student->id,$student->email);
        $flag="<<student!";
        $subs_count_cache_with_header=explode($flag,$cache);
        $institution=Institution::find(Student::find(Student::return_id())->institution_id);
        $stripe_id=$institution->stripe_id;

        $cache_data = 'wam_academy_data';
        $cache_data_encrypted= $this->EncryptionController->encryption($cache_data,'encrypt');
        if( $this->EncryptionController->encryption($this->CacheController->get_cache($institution->id,$institution->email,'ongoing_payment'), 'decrypt')== $cache_data){
            return $next($request);
        }

        if($cache != null){
            $count_subscription=$subs_count_cache_with_header[1];
        }

        else if($stripe_id == null && $institution->trial_ends_at < Carbon::now()){
            return response(['message'=>'Trial Ended, It id time to make things official'],410);
        }

        else if($stripe_id != null && $institution->trial_ends_at < Carbon::now()){
        $authority=Authority::first(['stripe_price_id',]);
        $subscriptions_array =  $this->stripe->subscriptions->all([
            'customer' => Institution::find(Student::find(Student::return_id())->institution_id)->stripe_id,
            //'price'=>$authority->stripe_price_id,
            'status'=>'active',
            'current_period_end'=>[
                'gte'=>Carbon::now()->timestamp
            ],
        ])->data;

        $count_subscription=count($subscriptions_array);

        $this->CacheController->make_subscription_cache($student->id,$student->email,$flag.$count_subscription.$flag);
        }

        else{
            return $next($request);
        }

//        $end_trial=$this->EncryptionController->encryption(Institution::find(Student::find(Student::return_id())->institution_id)->trial_ends_at ,'decrypt' );
//        $end_trial=Carbon::parse($end_trial);

//        if($count_subscription <= 0 && $end_trial < Carbon::now())
//            return response(['message'=>'Trial Ended, Contract Institution Admin for Reactivation'],500);

        if($count_subscription <= 0 && Institution::find(Student::find(Student::return_id())->institution_id)->trial_ends_at < Carbon::now()){
            try{
                $this->CacheController->make_cache($institution->id,$institution->email, $cache_data_encrypted,'ongoing_payment');
                $this->InstitutionApiPaymentController->subscribe($institution->id);
                return $next($request);
            } catch (\Exception $e){
                $this->CacheController->forget_cache($institution->id,$institution->email,'ongoing_payment');
                return response(['message'=>$e]);
            }
            //return response(['message'=>'Trial Ended, Contract Institution Admin for Reactivation'],500);
        }
        else{
            return $next($request);
        }

    }
}
