<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\EncryptionController;
use App\Http\Controllers\Helper\OTPController;
use App\Http\Controllers\Institution\InstitutionApiPaymentController;
use App\Models\Authority;
use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use App\Models\Transaction;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class CheckIfInstitutionOfFacilitatorSubscribed
{
    protected $stripe,$CacheController,$EncryptionController, $InstitutionApiPaymentController , $OTPController;
    public function __construct(CacheController $CacheController, EncryptionController $EncryptionController , InstitutionApiPaymentController $InstitutionApiPaymentController , OTPController $OTPController){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $this->CacheController=$CacheController;
        $this->EncryptionController=$EncryptionController;
        $this->OTPController=$OTPController;
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

        $facilitator=Facilitator::find(Facilitator::return_id());
        $cache=$this->CacheController->get_subscription_cache($facilitator->id,$facilitator->email);
        $flag="<<facilitator!";
        $subs_count_cache_with_header=explode($flag,$cache);
        $institution=Institution::find(Facilitator::find(Facilitator::return_id())->institution_id);
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
                'customer' => Institution::find(Facilitator::find(Facilitator::return_id())->institution_id)->stripe_id,
                //'price'=>$authority->stripe_price_id,
                'status'=>'active',
                'current_period_end'=>[
                    'gte'=>Carbon::now()->timestamp
                ],
            ])->data;

            $count_subscription=count($subscriptions_array);

            $this->CacheController->make_subscription_cache($facilitator->id,$facilitator->email,$flag.$count_subscription.$flag);
        }

        else{
            return $next($request);
        }

        $transactions = Transaction::Where([
            ['institution_id',$institution->id],
            ['transaction_date','=',Carbon::now()->toDateString()]
            ])->orWhere([
            ['institution_id',$institution->id],
            ['transaction_date','>=',Carbon::now()->subMonth()->toDateString()]
        ])->count();

        if($transactions==0 && $count_subscription <= 0 && Institution::find(Facilitator::find(Facilitator::return_id())->institution_id)->trial_ends_at < Carbon::now()){
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
