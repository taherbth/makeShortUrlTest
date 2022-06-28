<?php

namespace App\Http\Controllers\Institution;

use App\Events\OTPrequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Facilitator\ApiAuthController;
use App\Http\Controllers\Helper\CookieController;
use App\Http\Controllers\Helper\EncryptionController;
use App\Http\Controllers\Helper\OTPController;
use App\Http\Requests\EmailResetRequest;
use App\Http\Requests\OTPVerifyRequest;
use App\Http\Requests\PasswordVerifyRequest;
use App\Models\Institution;
use App\Rules\ifExistsInDB;
use http\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class InstitutionApiAuthController extends Controller
{

    protected $CookieController;
    protected $OTPController;
    protected $EncryptionController;

    public function __construct(CookieController $CookieController, EncryptionController $EncryptionController, OTPController $OTPController){
        $this->CookieController = $CookieController;
        $this->EncryptionController = $EncryptionController;
        $this->OTPController = $OTPController;
    } // Get Cookie Controller Controller Properties


    public function logout(){

        $user=Institution::find(Institution::return_id());
        if(Cache::has($user->id.'institution_subscribed'.$user->email))
            Cache::forget($user->id.'institution_subscribed'.$user->email);
        if(Cache::has($user->id.'bill_estimation'.$user->email))
            Cache::forget($user->id.'institution_subscribed'.$user->email);

        try{
            Auth::guard('api-institution')->user()->token()->revoke();
            Session::flush();
            $this->CookieController->flush_cookies_except();
            return response(['message' => 'Successfully Logged Out']);
        }catch (\Exception $e){
            report($e);
            return response(['message' => 'Error Occurred'],500);
        }
    }

    public function email_reset_request(){
        $email = Auth::guard('api-institution')->user()->email;
        $random = Str::random(6);

        try {
            $this->OTPController->EmailResetOTPSend($random, $email);     //OTP Email Send
            $cookie=cookie('password_reset_otp',$this->EncryptionController->encryption($random,'encrypt'),3);
            return response([
                'message' => 'Otp Send Successfully',
                'user' => $email,
                //'otp' => $random
            ])->withCookie($cookie);
        } catch (\Exception $e) {
            report($e);
            return response(['message' => 'Error Occurred'],500);
        }
    }

    public function email_reset_otp_verify(OTPVerifyRequest $request){

        if(!isset ($_COOKIE['password_reset_otp'])){
            return response(['message'=>'Session Expired, Please request again'],500);
        }

    try{
        $otp=$this->EncryptionController->encryption(Cookie::get('password_reset_otp'),'decrypt');

        if ($otp == $request->otp) {
            $cookie=cookie('password_reset_verified_email',$this->EncryptionController->encryption(Auth::guard('api-institution')->user()->email,'encrypt'),3);
            return response(['message' => 'Success'])->withCookie($cookie);

        } else {
            return response(['message' => 'Invalid OTP'],500);
        }
    }
    catch(\Exception $e){
            report($e);
            return response(['message'=> 'Error Occurred'],500);
        }
    }

    public function email_reset(EmailResetRequest $request){
        if(!isset ($_COOKIE['password_reset_verified_email'])){
            return response(['message'=>'Session Expired, Please request again'],500);
        }


        $user = Auth::guard('api-institution')->user()->email;
        if($user==html_entity_decode($this->EncryptionController->encryption(Cookie::get('password_reset_verified_email'),'decrypt'))) {
            try {
                $institution=Institution::where('email', '=', $user)->first();
                $institution->email=$request->email;
                $institution->save();



                if($institution->stripe_id != null){
                    $stripe = new \Stripe\StripeClient(
                        env('STRIPE_SECRET')
                    );
                    $stripe->customers->update(
                        $institution->stripe_id,
                        ['email' => $request->email]
                    );
                }
                return response(['message' => 'Email Updated Successfully']);

            } catch (\Exception $e) {
                report($e);
                return response(['message' => 'Error Occurred'], 500);
            }
        }
        else{
            return response(['message' => 'Email is not verified' , ], 500);
        }
    }

    public function password_reset(PasswordVerifyRequest $request){
        $current_token_id= Auth::guard('api-institution')->user()->token()->id;
        $institution =Institution::find(Institution::return_id());

        if(!Hash::check($request->old_password, $institution->password))
            return response(['message'=>'Old password Doesnt match'],500);

        if($request->password == $request->old_password)
            return response(['message'=>'You have given the same password'],500);


        $institution->password=Hash::make($request->password);
        $institution->save();

        $tokens= Auth::guard('api-institution')
            ->user()
            ->tokens
            ->where('role',3)
            ->where('id','!=',$current_token_id);

        foreach ($tokens as $token)
            $token->revoke();

        return response(['message'=>'Password Changed Successfully']);

    }

}
