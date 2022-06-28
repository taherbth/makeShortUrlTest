<?php

namespace App\Http\Controllers\Authority;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\CookieController;
use App\Http\Controllers\Helper\EncryptionController;
use App\Http\Controllers\Helper\OTPController;
use App\Http\Requests\EmailResetRequest;
use App\Http\Requests\OTPVerifyRequest;
use App\Http\Requests\PasswordVerifyRequest;
use App\Models\Authority;
use App\Rules\ifExistsInDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthorityApiAuthController extends Controller
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
        try{
            Auth::guard('api-authority')->user()->token()->revoke();
            Session::flush();
            $this->CookieController->flush_cookies_except();
            return response(['message' => 'Successfully Logged Out']);
        }catch (\Exception $e){
            report($e);
            return response(['message' => 'Error Occurred'],500);
        }
    }

    public function email_reset_request()
    {
        $email = Auth::guard('api-authority')->user()->email;
        $random = Str::random(6); //generate OTP

        try {
            $this->OTPController->EmailResetOTPSend($random, $email);     //OTP Email Send

            $cookie = cookie('password_reset_otp', $this->EncryptionController->encryption($random, 'encrypt'), 3);
            return response([
                'message' => 'Otp Send Successfully',
                'user' => $email,
                //'otp' => $random
            ])->withCookie($cookie);
        } catch (\Exception $e) {
            report($e);
            return response(['message' => 'Error Occurred'], 500);
        }
    }

    public function email_reset_otp_verify(OTPVerifyRequest $request)
    {
        if (!isset ($_COOKIE['password_reset_otp'])) {
            return response(['message' => 'Session Expired, Please request again'], 500);
        }  //Check if the user is new

        try {
            $otp = $this->EncryptionController->encryption(Cookie::get('password_reset_otp'), 'decrypt');
            if ($otp == $request->otp) { //Checking OTP
                $cookie = cookie('password_reset_verified_email', $this->EncryptionController->encryption(Auth::guard('api-authority')->user()->email, 'encrypt'), 3);
                return response(['message' => 'Success'])->withCookie($cookie);

            } else {
                return response(['message' => 'Invalid OTP'], 500);
            }
        } catch (\Exception $e) {
            report($e);
            return response(['message' => 'Error Occurred'], 500);
        }
    }

    public function email_reset(EmailResetRequest $request)
    {
        if (!isset ($_COOKIE['password_reset_verified_email'])) {
            return response(['message' => 'Session Expired, Please request again'], 500);
        }


        $user =  Auth::guard('api-authority')->user()->email;
        if ($user == html_entity_decode($this->EncryptionController->encryption(Cookie::get('password_reset_verified_email'), 'decrypt'))) {
            //checking if the user is activated or not
            try {
                $authority=Authority::where('email', '=', $user)->first();
                $authority->email= $request->email;
                $authority->save();

                return response(['message' => 'Email Updated Successfully']);
            } catch (\Exception $e) {
                report($e);
                return response(['message' => 'Error Occurred, can not update Email'], 500);
            }
        } else {
            return response(['message' => 'Email is not verified',], 500);
        }
    }

    public function password_reset(PasswordVerifyRequest $request){
        $current_token_id= Auth::guard('api-authority')->user()->token()->id;
        $authority=Authority::find(Authority::return_id());

        if(!Hash::check($request->old_password, $authority->password))
            return response(['message'=>'Old password Doesnt match'],500);

        if($request->password == $request->old_password)
            return response(['message'=>'You have given the same password'],500);


        $authority->password=Hash::make($request->password);
        $authority->save();

        $tokens= Auth::guard('api-authority')
            ->user()
            ->tokens
            ->where('role',4)
            ->where('id','!=',$current_token_id);

        foreach ($tokens as $token)
            $token->revoke();

        return response(['message'=>'Password Changed Successfully']);

    }
}
