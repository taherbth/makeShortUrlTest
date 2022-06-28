<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthoritySetPricingRequest;
use App\Models\Authority;
use Illuminate\Http\Request;

class AuthorityApiPricingController extends Controller
{
    protected $stripe,$authority;
    public function __construct(){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $this->authority=Authority::find(Authority::return_id());
    }

    public function post_set_package_pricing(AuthoritySetPricingRequest $request){
        if(is_float($request->tax_rate))
            return response(['message'=>'Tax can can not be float'],500);

        if($this->authority->stripe_price_id != null){
            $this->stripe->plans->delete(
                $this->authority->stripe_price_id,
                []
            );
        }

        $price = $this->stripe->prices->create([
            'unit_amount' => $request->unit_amount * 100,
            'currency' => 'usd',
            'recurring' => ['interval' => 'month'],
            'product' => $this->authority->stripe_product_id,
        ]);

        if($request->is_taxed == 0)
            $tax_id=null;

        else if($request->is_taxed == 1){
            $tax = $this->stripe->taxRates->create([
                'display_name' => 'VAT',
                'description' => 'VAT for institution subscription of '.env('APP_NAME'),
                'jurisdiction' => 'US',
                'percentage' => $request->tax_rate,
                'inclusive' => false,
            ]);
            $tax_id=$tax["id"];
        }

        $this->authority->stripe_price_id=$price["id"];
        $this->authority->stripe_tax_id=$tax_id;
        $this->authority->save();

        return response(['message'=>'Pricing Updated']);
    }

    public function get_package_pricing(){

        $unit_amount = $this->stripe->prices->retrieve(
            $this->authority->stripe_price_id,
            []
        )["unit_amount"];

        if($this->authority->stripe_tax_id != null)
        {
            $tax_rate = $this->stripe->taxRates->retrieve(
            $this->authority->stripe_tax_id,
            []
            )["percentage"];
        }
        else{
            $tax_rate=0;
        }

        return response([
            'unit_amount'=>$unit_amount,
            'tax_rate'=>$tax_rate,
        ]);
    }
}
