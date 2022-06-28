<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Requests\InstitutionCreateBillingAddressRequest;
use App\Http\Requests\InstitutionEditBillingAddressRequest;
use App\Http\Requests\InstitutionPostDefaultBillingRequest;
use App\Http\Requests\InstitutionSetDefaultBillingAddressRequest;
use App\Models\BillingAddress;
use App\Models\City;
use App\Models\Country;
use App\Models\Institution;
use App\Models\State;
use League\ISO3166\ISO3166;
use Illuminate\Http\Request;

class InstitutionApiBillingController extends Controller
{
    protected $stripe,$institution;
    public function __construct(){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $this->institution = Institution::find(Institution::return_id());
    }

    public function post_create_billing_address(InstitutionCreateBillingAddressRequest $request){
        if(Institution::return_stripe_id() == null)
            return response(['message'=>'Please Add a Payment Method First'],500);

//        try{
//            $request->country = (new ISO3166)->name($request->country)["alpha2"];
//        }catch(\Exception $e){ }

        $billing=$this->institution->billing_address_i()->create([
            'country_id'=>$request->country_id,
            'zip_code' => $request->zip_code,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'email' => $request->email,
        ]);

        if($this->institution->billing_address_i()->count() == 1){
            $billing->is_default=1;
            $billing->save();

            $this->stripe->customers->update(
                Institution::return_stripe_id(),
                ['address' =>
                    [
                        'country' => $request->country,
                        'city' => $request->city,
                        'line1' => $request->address_line_1,
                        'line2' => $request->address_line_2,
                        'postal_code' => $request->zip_code,
                        'state' => $request->state,
                    ],
                ]
            );
        }
        else if ($this->institution->billing_address_i()->count() > 1 && $request->has('is_default') && $request->is_default ==1){
            $billing->is_default=$request->is_default;
            $billing->save();

            $other_billing_addresses= $this->institution->billing_address_i()->where([['is_default',1],['id','!=',$billing->id]])->get();
            foreach ($other_billing_addresses as $other_billing_address){
                $other_billing_address->is_default=0;
                $other_billing_address->save();

            }

            $this->stripe->customers->update(
                Institution::return_stripe_id(),
                ['address' =>
                    [
                        'country' => $request->country,
                        'city' => $request->city,
                        'line1' => $request->address_line_1,
                        'line2' => $request->address_line_2,
                        'postal_code' => $request->zip_code,
                        'state' => $request->state,
                    ],
                ]
            );


        }

        return response(['message'=>'Billing Address Set Successfully']);

    }


    public function post_set_default_billing_address(InstitutionSetDefaultBillingAddressRequest $request){
        $id=$request->id;

        $billing_address=BillingAddress::find($id);

        if($billing_address == null)
            return response(['message'=>'Billing Address Not Found'],500);

        if($billing_address->institution_id != Institution::return_id())
            return response(['message'=>'Not Your Billing Address'],500);

        $billing_address->is_default=1;
        $billing_address->save();

        $other_billing_addresses=BillingAddress::where([
            ['institution_id',Institution::return_id()],
            ['is_default',1],
            ['id','!=',$billing_address->id],
        ])->get();

        foreach ($other_billing_addresses as $other_billing_address){
            $other_billing_address->is_default=0;
            $other_billing_address->save();
        }

        if(Institution::return_stripe_id() != null){

            $this->stripe->customers->update(
                Institution::return_stripe_id(),
                ['address' =>
                    [
                        'country' => Country::find($billing_address->country_id)->sortname,
                        'city' => City::find($billing_address->city_id)->name,
                        'line1' => $billing_address->address_line_1,
                        'line2' => $billing_address->address_line_2,
                        'postal_code' => $billing_address->zip_code,
                        'state' => State::find($billing_address->state)->name,
                    ],
                ]
            );
        }

        return response(['message'=>'Billing Address Updated Successfully']);
    }

    public function get_all_billing_addresses(){
        $billing_addresses= $this->institution->billing_address_i()->get();
        foreach ($billing_addresses as $billing_address){
            $billing_address->country=Country::find($billing_address->country_id)->name;
            $billing_address->city=City::find($billing_address->city_id)->name;
            $billing_address->state=State::find($billing_address->state_id)->name;
        }
        return $billing_addresses;
    }

    public function post_edit_billing_address( InstitutionEditBillingAddressRequest $request){
//        try{
//            $request->country = (new ISO3166)->name($request->country)["alpha2"];
//        }catch(\Exception $e){ }

        $billing=BillingAddress::find($request->id);

        if($billing == null)
            return response(['message'=>'No Such Billing Address Exists'],500);

        if(	$billing->institution_id != Institution::return_id())
            return response(['message'=>'Not Your Billing Address'],500);

        $billing->update(array(
            "country"=>$request->country_id,
            "zip_code"=>$request->zip_code,
            "state"=>$request->state_id,
            "city"=>$request->city_id,
            "address_line_1"=>$request->address_line_1,
            "address_line_2"=>$request->address_line_2,
            "email"=>$request->email,
        ));

        return response(['message'=>'Billing Updated Successfully']);

    }

    public function get_edit_billing_address($id){
        $billing= BillingAddress::find($id);
        if($billing == null)
            return response(['message'=>'Billing Address Not Found'],500);
        return $billing;
    }

    public function get_all_countries(){
        return Country::all();
    }

    public function get_all_states_in_countries($id){
        $country=Country::find($id);
        if($country == null)
            return response(['message'=>'Country Doesnt Exists'],500);
        return $country->states()->get();
    }

    public function get_all_cities_in_states($id){
        $state=State::find($id);
        if($state==null)
            return response(['message'=>'State Doesnt Exists'],500);
        return $state->cities()->get();

    }

}
