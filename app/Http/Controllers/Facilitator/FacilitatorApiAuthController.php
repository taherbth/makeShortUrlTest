<?php

namespace App\Http\Controllers\Facilitator;

use App\Events\OTPrequest;
use App\Events\VideoUploadEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailResetRequest;
use App\Http\Requests\VideoInfoUploadRequest;
use App\Http\Requests\VideoUploadRequest;
use App\Http\Requests\OTPVerifyRequest;
use App\Http\Requests\PasswordVerifyRequest;
use App\Http\Requests\UploadThumbnailRequest;
use App\Models\Course;
use App\Models\courseQuestion;
use App\Models\Facilitator;

use App\Models\Institution;
use App\Models\Student;
use App\Models\studentResponses;
use App\Rules\ifExistsInDB;
use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\X264;
use http\Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Lakshmaji\Thumbnail\Thumbnail;
use ProtoneMedia\LaravelFFMpeg\FFMpeg\FFProbe;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use SplFixedArray;
use function PHPUnit\Framework\isEmpty;
use App\Http\Controllers\Helper\CookieController;
use App\Http\Controllers\Helper\EncryptionController;
use App\Http\Controllers\Helper\OTPController;


class FacilitatorApiAuthController extends Controller
{
    protected $CookieController;
    protected $OTPController;
    protected $EncryptionController;

    public function __construct(CookieController $CookieController, EncryptionController $EncryptionController, OTPController $OTPController){
        $this->CookieController = $CookieController;
        $this->EncryptionController = $EncryptionController;
        $this->OTPController = $OTPController;
    } // Get Cookie Controller Controller Properties



    public function logout()
    {
        $user=Facilitator::find(Facilitator::return_id());
        if(Cache::has($user->id.'institution_subscribed'.$user->email))
            Cache::forget($user->id.'institution_subscribed'.$user->email);
        try {
            Auth::user()->token()->revoke();
            $this->CookieController->flush_cookies_except();
            Session::flush();
            return response(['message' => 'Successfully Logged Out']);
        } catch (\Exception $e) {
            report($e);
            return response(['message' => 'Error Occurred'], 500);
        } //logging user out
    }

    public function email_reset_request()
    {
        $email = Auth::guard('api-facilitator')->user()->email;
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
                $cookie = cookie('password_reset_verified_email', $this->EncryptionController->encryption(Auth::guard('api-facilitator')->user()->email, 'encrypt'), 3);
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


        $user =  Auth::guard('api-facilitator')->user()->email;
        if ($user == html_entity_decode($this->EncryptionController->encryption(Cookie::get('password_reset_verified_email'), 'decrypt'))) {
            //checking if the user is activated or not
            try {
                $facilitator=Facilitator::where('email', '=', $user)->first();
                $facilitator->email= $request->email;
                $facilitator->save();

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
        $current_token_id= Auth::guard('api-facilitator')->user()->token()->id;
        $facilitator=Facilitator::find(Facilitator::return_id());

        if(!Hash::check($request->old_password, $facilitator->password))
            return response(['message'=>'Old password Doesnt match'],500);

        if($request->password == $request->old_password)
            return response(['message'=>'You have given the same password'],500);


        $facilitator->password=Hash::make($request->password);
        $facilitator->save();

        $tokens= Auth::guard('api-facilitator')
            ->user()
            ->tokens
            ->where('role',1)
            ->where('id','!=',$current_token_id);

        foreach ($tokens as $token)
            $token->revoke();

        return response(['message'=>'Password Changed Successfully']);

    }




}
