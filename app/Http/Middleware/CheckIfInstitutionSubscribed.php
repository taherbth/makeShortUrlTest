<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\EncryptionController;
use App\Http\Controllers\Institution\InstitutionApiPaymentController;
use App\Models\Authority;
use App\Models\Institution;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CheckIfInstitutionSubscribed
{
    protected $stripe,$CacheController,$EncryptionController, $bill_estimation_cache_type ,$cache_type_invoice_history ,$InstitutionApiPaymentController ,$institution;
    public function __construct(CacheController $CacheController, EncryptionController $EncryptionController, InstitutionApiPaymentController $InstitutionApiPaymentController){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $this->CacheController=$CacheController;
        $this->EncryptionController=$EncryptionController;
        $this->InstitutionApiPaymentController=$InstitutionApiPaymentController;
        $this->bill_estimation_cache_type='bill_estimation';
        $this->cache_type_invoice_history='invoice_history';
        $this->institution=Institution::find(Institution::return_id());
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
        $institution=Institution::find(Institution::return_id());
        $cache=$this->CacheController->get_subscription_cache($institution->id,$institution->email);
        $flag="<<institution!";
        $subs_count_cache_with_header=explode($flag,$cache);
        $cache_data = 'wam_academy_data';
        $cache_data_encrypted= $this->EncryptionController->encryption($cache_data,'encrypt');
        if( $this->EncryptionController->encryption($this->CacheController->get_cache($institution->id,$institution->email,'ongoing_payment'), 'decrypt')== $cache_data){
            return $next($request);
        }
        if($cache != null){
            $count_subscription=$subs_count_cache_with_header[1];
        }
        else if(Institution::return_stripe_id() == null && Institution::find(Institution::return_id())->trial_ends_at < Carbon::now()){
            $this->CacheController->forget_pagination_cache($institution->id, $institution->email,$this->cache_type_invoice_history,1);
            $this->CacheController->forget_subscription_cache($institution->id, $institution->email);
            $this->CacheController->forget_cache($institution->id,$institution->email, $this->bill_estimation_cache_type);
            return response(['message'=>'Trial Ended, It id time to make things official'],410);
        }

        else if(Institution::return_stripe_id() != null && Institution::find(Institution::return_id())->trial_ends_at < Carbon::now()){
        $authority=Authority::first(['stripe_price_id',]);
        $subscriptions_array =  $this->stripe->subscriptions->all([
            'customer' => Institution::return_stripe_id(),
            //'price'=>$authority->stripe_price_id,
            'status'=>'active',
            'current_period_end'=>[
                'gte'=>Carbon::now()->timestamp
            ],
        ])->data;
        $count_subscription=count($subscriptions_array);

        $this->CacheController->make_subscription_cache($institution->id,$institution->email,$flag.$count_subscription.$flag);
        }

        else{
            return $next($request);
        }

//        $end_trial=$this->EncryptionController->encryption(Institution::find(Institution::return_id())->trial_ends_at) ,'decrypt' );
//        $end_trial=Carbon::parse($end_trial);

//        if($count_subscription <= 0 && $end_trial < Carbon::now())
//            return response(['message'=>'Trial Ended, It id time to make things official'],410);

        if($count_subscription <= 0 && $institution->trial_ends_at < Carbon::now()){
            try{
                $this->CacheController->make_cache($institution->id,$institution->email, $cache_data_encrypted,'ongoing_payment');
                $this->CacheController->forget_pagination_cache($institution->id, $institution->email,$this->cache_type_invoice_history,1);
                $this->CacheController->forget_subscription_cache($institution->id, $institution->email);
                $this->CacheController->forget_cache($institution->id,$institution->email, $this->bill_estimation_cache_type);
                $this->InstitutionApiPaymentController->subscribe($this->institution->id);
                return $next($request);
            }catch (\Exception $e){
                $this->CacheController->forget_cache($institution->id,$institution->email,'ongoing_payment');
                return response(['message'=>$e]);
            }

            //return response(['message'=>'Trial Ended, It id time to make things official'],410);
        }

        else{
            return $next($request);
        }

    }
}
