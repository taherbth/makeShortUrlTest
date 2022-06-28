<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\EncryptionController;
use App\Models\Authority;
use App\Models\Institution;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class CheckIfInstitutionNotSubscribed
{

    protected $stripe;
    public function __construct(){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

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


        if(Institution::return_stripe_id() == null && $institution->trial_ends_at < Carbon::now()){
            return $next($request);
        }

        else if(Institution::return_stripe_id() != null && $institution->trial_ends_at < Carbon::now()){
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

            if($count_subscription <= 0 && $institution->trial_ends_at < Carbon::now())
                return $next($request);
            else{
                return response(['message'=>'Your Subscription Is Already Active'],500);
            }

        }

        else{
            return response(['message'=>'Your Subscription Is Already Active'],500);
        }

    }
}
