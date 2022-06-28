<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CacheController;
use App\Http\Controllers\Helper\OTPController;
use App\Http\Requests\InstitutionAddNewPaymentRequest;
use App\Http\Requests\InstitutionChangeDefaultPaymentRequest;
use App\Http\Requests\InstitutionPaymentRequest;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Authority;
use App\Models\Institution;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;


class InstitutionApiPaymentController extends Controller
{
    protected $stripe,$institution, $CacheController ,$bill_estimation_cache_type,$payment_method_list_cache_type ,$cache_type_invoice_history ,$OTPController;
    public function __construct(CacheController $CacheController, OTPController $OTPController){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $this->institution = Institution::find(Institution::return_id());
        $this->CacheController = $CacheController;
        $this->OTPController = $OTPController;
        $this->bill_estimation_cache_type='bill_estimation';
        $this->payment_method_list_cache_type='payment_methods_list';
        $this->cache_type_invoice_history='invoice_history';
    }

    public function add_payment_method(InstitutionAddNewPaymentRequest $request){
        $this->CacheController->forget_cache($this->institution->id,$this->institution->email, $this->payment_method_list_cache_type);
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $institution = Institution::find(Institution::return_id());
        $source=$this->stripe->sources->create([
            "type" => "card",
            "currency" => "usd",
            "card" => [
                'exp_month' => explode('/',$request->expiry)[0], //11
                'exp_year' => explode('/',$request->expiry)[1], //2022, //
                'number'=>$request->number, //'4242 4242 4242 4242', //
                'cvc'=>$request->cvc //'456'
            ]
        ]);

        if($institution->stripe_id == null){
            $institution_stripe=$this->stripe->customers->create([
                'email'=>$institution->email,
                'name'=>$institution->institution_name,
                'source'=>$source->id,
            ]);

            $institution->stripe_id =$institution_stripe->id;
            $institution->save();

            $this->stripe->paymentMethods->attach(
                $source->id,
                [
                    'customer' => $institution_stripe->id,
                ]
            );
        }
        else{

             \Stripe\Customer::createSource(
                $institution->stripe_id,
                [
                    'source' => $source->id,
                ]
            );

            if($request->is_primary == 1){
                $this->stripe->customers->update(
                    $this->institution->stripe_id,
                    ['default_source' => $source->id]
                );
            }

        }


        return response(['message'=>'Payment Method Added Successfully']);

    }

    public function get_all_payment_methods(){

        if($this->institution->stripe_id == null)
            return response(['message','Please add a payment method first']);

        else{
            $cache=$this->CacheController->get_cache($this->institution->id,$this->institution->email,$this->payment_method_list_cache_type);

            if($cache!= null){
                $trimmed_cache=$this->CacheController->trim_cache($cache);
                return response($trimmed_cache);
            }

            else{
                $all_payments= $this->stripe->paymentMethods->all([
                    'customer' => $this->institution->stripe_id,
                    'type' => 'card',
                ]);

                $primary_payment= $primary_payments= $this->stripe->customers->retrieve(
                    $this->institution->stripe_id,
                    []
                )->default_source;

                $data=['all_payment'=>$all_payments, 'primary_payment'=>$primary_payment];

                $this->CacheController->make_cache($this->institution->id,$this->institution->email,$data, $this->payment_method_list_cache_type);

                return response($data);
            }
        }

    }

    public function post_change_default_payment_method(InstitutionChangeDefaultPaymentRequest $request){

        try{
            $this->stripe->customers->update(
                $this->institution->stripe_id,
                ['default_source' => $request->id]
            );
            $this->CacheController->forget_cache($this->institution->id,$this->institution->email, $this->payment_method_list_cache_type);
            return response(['message'=>'Default Payment Changed']);
        } catch(\Exception $e){
            return response(['message'=>'Payment Status Not Changed'],500);
        }
    }


    public function subscribe($id){
        //if($request->has('id')){
            $institution=Institution::find($id);
            $institution_stripe_id=$institution->stripe_id;
        //}else{
//            $institution=$this->institution;
//            $institution_stripe_id=$institution->stripe_id;
//        }

        $authority=Authority::first(['stripe_price_id','stripe_tax_id']);
        $stripe_price=$authority->stripe_price_id;
        $stripe_tax=$authority->stripe_tax_id;
        $total_members= $institution->student_s()->where('status',1)->count()+$institution->facillitor_s()->where('status',1)->count() + 1;

        if($institution_stripe_id == null)
            return response(['message'=>'Please Attach a Payment Method First'],500);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        if($stripe_tax==null){
            $subscription=\Stripe\Subscription::create([
                'customer' => $institution_stripe_id,
                'items' => [
                    [
                        'price' => $stripe_price,
                        'quantity' => $total_members,
                    ],
                ],
                'cancel_at_period_end'=>true

            ]);
            $tax=0;
        }
        else{
            $subscription=\Stripe\Subscription::create([
                'customer' => $institution_stripe_id,
                'items' => [
                    [
                        'price' => $stripe_price,
                        'quantity' => $total_members,
                    ],
                ],
                'default_tax_rates'=>[$stripe_tax],
                'cancel_at_period_end'=>true

            ]);
            //return $subscription;

            $tax=$subscription->default_tax_rates[0]["percentage"];
        }

        $base_price=$subscription->items["data"][0]["price"]["unit_amount"];
        $quantity=$subscription->quantity;
        $total_charge=($base_price * $quantity)+(($base_price * $quantity) * ($tax/100));

        if($institution->transactions()->first())
            $institution->transactions()->first()->update([
                'transaction_date' =>Carbon::now()->toDateString()
            ]);
        else
            $institution->transactions()->create([
                'transaction_date' =>Carbon::now()->toDateString()
            ]);

        $institution->invoice_s()->create([
            'tax'=>$tax,
            'base_price'=>$base_price,
            'quantity'=>$quantity,
            'total_paid'=>$total_charge,
            'status'=>$subscription->status,
            'subscription_id'=>$subscription->id,
            'cancel_at'=>$subscription->cancel_at,
            'institution_name'=>$institution->institution_name,
            'admin_name'=>$institution->admin_name

        ]);


        $this->CacheController->forget_pagination_cache($institution->id, $institution->email,$this->cache_type_invoice_history,1);
        $this->CacheController->forget_subscription_cache($institution->id, $institution->email);
        $this->CacheController->forget_cache($institution->id, $institution->email, $this->bill_estimation_cache_type);
        $invoice= $subscription->latest_invoice;
        $subscription=$this->stripe->invoices->retrieve(
            $invoice,
            []
        )["hosted_invoice_url"];
        $this->OTPController->institution_payment_sucessful_mail($institution->email,$subscription);

        return response(['message'=>'Subscription Successful']);
    }

    //             $customer=$this->stripe->customers->retrieve(
//                $institution->stripe_id,
//                []
//            );

//

//        $charge = $this->stripe->charges->create([
//            'amount' => 1000,
//            'currency' => 'usd',
//            'customer' => 'cus_IzWUHBfWzx9NS2',
//            'source' => 'src_1INXR1Gi9qX1mHfntrPcn9tM',
//        ]);
//        return $charge;



//        $data = $stripe->setupIntents->create([
//            'payment_method_types' => ['card'],
//            'customer'=>$user->createAsStripeCustomer()->id,
//            'plan' => 'price_1IM6kZGi9qX1mHfnpEXjGAOu'
//        ]);

        //return $stripe->plans->all();


//
//        $availablePlans =[
//            'monthly' => "Monthly",
//        ];
//        $data = [
//            'intent' => $user->createSetupIntent( ['usage' => 'on_session']),
//            'plans'=> 'price_1IM6kZGi9qX1mHfnpEXjGAOu',
//            'customer'=> $user,
//        ];
//        return $data;
//        $stripe->setupIntents->retrieve(
//            'seti_1IMQtbGi9qX1mHfn2rbIwTOu_secret_IyNeAaB1rHpYQQ1WvC9E1qQuSXLaumQ',
//            []
//        );
        //seti_1IMQtbGi9qX1mHfn2rbIwTOu_secret_IyNeAaB1rHpYQQ1WvC9E1qQuSXLaumQ


    //        $a=$stripe->tokens->create([
//            'card' => [
//                'number' => '4242424242424242',
//                'exp_month' => 2,
//                'exp_year' => 2022,
//                'cvc' => '314',
//            ],
//        ]);
    //return $a;


//        return $stripe->tokens->retrieve(
//            'tok_1IMPyRGi9qX1mHfnXKsVdaRL',
//            []
//        );

//        $availablePlans =[
//            'monthly' => "Monthly",
//        ];
//        $data = [
//            'intent' => $user->createSetupIntent(),
//            'plans'=> $availablePlans
//        ];

}
