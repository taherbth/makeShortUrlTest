<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Models\Authority;
use App\Models\Institution;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class InstitutionEstimationController extends Controller
{
    protected $stripe, $CacheController, $bill_estimation_cache_type;
    public function __construct(CacheController $CacheController){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $this->CacheController=$CacheController;
        $this->bill_estimation_cache_type='bill_estimation';
    }
    public function get_billing_estimation(){
        $institution=Institution::find(Institution::return_id());

        $cache=$this->CacheController->get_cache($institution->id,$institution->email,$this->bill_estimation_cache_type);

        if($cache != null)
            return $cache;

        else{
            $authority=Authority::first(['stripe_price_id','stripe_tax_id']);

            try{
                $price = $this->stripe->prices->retrieve(
                    $authority->stripe_price_id,
                    []
                )->unit_amount;
            } catch(\Exception $e){
            $price=0;
        }

            if($authority->stripe_tax_id!= null){
                $tax = $this->stripe->taxRates->retrieve(
                $authority->stripe_tax_id,
                []
            )->percentage;
            }else{
                $tax=0;
            }

            try{
                $cancel_at=$this->stripe->subscriptions->all([
                    'limit' => 1,
                    'customer'=>Institution::return_stripe_id(),
                    //'price'=>$authority->stripe_price_id,
                    'status'=>'active',
                    'current_period_end'=>[
                        'gte'=>Carbon::now()->timestamp
                    ],
                ])->data[0]["current_period_end"];
            } catch(\Exception $e){
                $cancel_at=0;
            }


            $total_user_count=$institution->student_s()->where('status',1)->count() + $institution->facillitor_s()->where('status',1)->count() +1;

            $data=[
                'pricing'=>$price,
                'tax'=>$tax,
                'total_user_count'=>$total_user_count,
                'canceled_at'=>$cancel_at,
                'trial_end_at'=>$institution->trial_ends_at
            ];

            $this->CacheController->make_cache($institution->id,$institution->email,$data,$this->bill_estimation_cache_type);

            return response(
                $data
            );
        }


    }
}
