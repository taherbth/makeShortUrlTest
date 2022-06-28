<?php

namespace App\Http\Controllers\Institution;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class InstitutionSetupController extends Controller
{
    protected $stripe;
    public function __construct(){
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
    }


}
