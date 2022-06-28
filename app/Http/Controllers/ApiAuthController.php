<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Facilitator\FacilitatorApiAuthController;
use App\Http\Controllers\Helper\CacheController;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Requests\EmailVerifyRequest;
use App\Http\Requests\ForgetPasswordOTPRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OTPVerifyRequest;
use App\Http\Requests\PasswordVerifyRequest;
use App\Models\Authority;
use App\Models\Facilitator;
use App\Models\Institution;
use App\Models\Student;
use App\Notifications\Authority\AuthorityNewInstitutionRegistered;
use App\Rules\ifExistsInDB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Throwable;
use App\Http\Controllers\Helper\CookieController;
use App\Http\Controllers\Helper\EncryptionController;
use App\Http\Controllers\Helper\OTPController;


class ApiAuthController extends Controller
{
    protected $CookieController;
    protected $OTPController;
    protected $EncryptionController;
    protected $stripe;
    protected $CacheController;
    protected $cache_type_authority_institution_list;


    public function __construct(CookieController $CookieController, EncryptionController $EncryptionController, OTPController $OTPController, CacheController $CacheController){
        $this->CookieController = $CookieController;
        $this->EncryptionController = $EncryptionController;
        $this->CacheController = $CacheController;
        $this->OTPController = $OTPController;
        $this->cache_type_authority_institution_list='authority_institution_list';
        $this->stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );

    } // Get Cookie Controller Controller Properties


    public function check_role($email): int
    {
        if(Facilitator::where('email','=',$email)->count()>0) {
            return 1;
        }
        else if(Student::where('email','=',$email)->count()>0){ return 2;}
        else if(Institution::where('email','=',$email)->count()>0){ return  3; }
        else if(Authority::where('email','=',$email)->count()>0){ return  4; }
        else {
            return 0;
        }
    }

// Controller Functions

    public function register(Request $request){

        $role=$request->role;

        if(
            (!isset ($_COOKIE['verified_email']) && !isset ($_COOKIE['institution_code']) && ($request->role == 1 || $request->role == 2))
            || (!isset ($_COOKIE['verified_email']) && $request->role == 3  && !isset ($_COOKIE['institution_code']))
        ){
            return response(['message'=>'Session Expired, Please request again'],500);
        }


        if($role != 3){
            $institution_code= $this->EncryptionController->encryption($_COOKIE['institution_code'],'decrypt');
            if($institution_code==null)
                return response(['message'=>'Error Occurred, Invalid Institution'],500);
            $institution=Institution::where('institution_code',$institution_code)->first();
            $institution_id=$institution->id;
            $institution_approval=$institution->user_approval;
        }

        if ($role == 3){

            $request->validate([
                'name'=>'required',
                'email'=>'required|email|unique:institutions,email',
                'password'=>'required|confirmed|min:8|regex:/^.*(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z])(?=\D*\d).{8,}.*$/',
                'role'=>'required|numeric|min:1|max:3',
                'institution_primary_student_quantity'=>'required|numeric',
                'institution_primary_facilitator_quantity'=>'required|numeric'

            ]);
            $authority= Authority::first();
            $this->CacheController->forget_pagination_cache($authority->id,$authority->email , $this->cache_type_authority_institution_list,1);
        }
        else if ($role == 2){
            if(!Institution::where('institution_code',$institution_code)->exists())
                return response(['message'=>'Institution doesnt exists'],500);

            $request->validate([
                'name'=>'required',
                'email'=>'required|email|unique:students,email',
                'password'=>'required|confirmed|min:8|regex:/^.*(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z])(?=\D*\d).{8,}.*$/',
                'institution_url' => 'required',
                'role'=>'required|numeric|min:1|max:3'

            ]);
        }
        else if ($role == 1){
            if(!Institution::where('institution_code',$institution_code)->exists())
                return response(['message'=>'Institution doesnt exists'],500);

            $request->validate([
                'name'=>'required',
                'email'=>'required|email|unique:facilitators,email',
                'password'=>'required|confirmed|min:8|regex:/^.*(?=[^a-z]*[a-z])(?=[^A-Z]*[A-Z])(?=\D*\d).{8,}.*$/',
                'institution_url' => 'required',
                'role'=>'required|numeric|min:1|max:3'

            ]);
        }
        else{
            return response(["message"=>"Session Expired, please try again"],500);
        }

        $password = Hash::make($request->password);
        $email=$request->email;
        $request =$request->toArray();
        $verified_email= $this->EncryptionController->encryption($_COOKIE['verified_email'],'verified_decrypt');
        $this->CookieController->flush_cookies();

        if($verified_email == $request['email']) {

            if ($role == 1) {
                if($institution_approval == 1)
                    $institution_approval = 0;
                else if($institution_approval == 0)
                    $institution_approval = 1;

                //try {

                    $facilitator= new Facilitator();
                    $facilitator->name=$request['name'];
                    $facilitator->email=$request['email'];
                    $facilitator->institution_id=$institution_id;
                    $facilitator->password=$password;
                    $facilitator->email_verified_at=Carbon::now();
                    $facilitator->status=$institution_approval;
                    $facilitator->save();

                    $this->OTPController->NewUserWelcomeMailSend($email,$role,$institution_id,$request['name']);
                    return response(['facilitator' => $facilitator]);
                //} catch (Throwable $e) {
                    //report($e);
                  //  return response(['message' => 'Error Occurred'], 500);
                //}
            } else if ($role == 2) {

                if($institution_approval == 1)
                    $institution_approval = 0;
                else if($institution_approval == 0)
                    $institution_approval = 1;

                try {

                    $student= new Student();
                    $student->name=$request['name'];
                    $student->email=$request['email'];
                    $student->institution_id=$institution_id;
                    $student->password=$password;
                    $student->email_verified_at=Carbon::now();
                    $student->status=$institution_approval;
                    $student->save();

                    $this->OTPController->NewUserWelcomeMailSend($email,$role,$institution_id,$request['name']);
                    return response(['student' => $student]);
                } catch (Throwable $e) {
                    report($e);
                    return response(['message' => 'Error Occurred'], 500);
                }
            } else if ($role == 3) {
                try{
                    $words = explode(" ", $request['name']);
                    $code = "";

                    foreach ($words as $w) {
                        $code .= $w[0];
                    }
                    //return $code;
                    $code=$this->increment_code(strtolower($code));


                    $institution= new Institution();
                    $institution->institution_name=$request['name'];
                    $institution->email=$request['email'];
                    $institution->password=$password;
                    $institution->institution_primary_student_quantity= $request['institution_primary_student_quantity'];
                    $institution->institution_primary_facilitator_quantity=$request['institution_primary_facilitator_quantity'];
                    $institution->email_verified_at=Carbon::now();
                    $institution->trial_ends_at=Carbon::now()->addDays(14);
                    //$institution->trial_ends_at=$this->EncryptionController->encryption(Carbon::now()->addDays(14) ,'encrypt' );
                    $institution->institution_code=$code;
                    $institution->save();

                } catch (Throwable $e) {
                    report($e);
                    return response(['message' => 'Error Occurred'], 500);
                }

                try{
                    $authorities=Authority::all();
                    Notification::send($authorities ,(new AuthorityNewInstitutionRegistered($institution->id)));
                } catch(\Exception $e){
                    return response(['message'=>'Error Notifying Students'],500);
                }
                $this->OTPController->NewUserWelcomeMailSend($email,$role,$institution->id,$request['name']);
                return response(['institution' => $institution]);

            }

        }

        else{
            return response(["message"=>"Email is not verified"],500);
        }




    }

    public function increment_code($code){

        $last_number=0;
        if(Institution::where('institution_code',$code)->exists()){
            //return 1;
            $last_digit=substr($code, -1);
            if(is_numeric($last_digit)) {
                //return 1;
                $last_digit++;
                $code=substr($code, 0,-1).$last_digit;
                return $this->increment_code($code);
            }
            else{
                $last_number++;
                $code=$code.$last_number;
                return $this->increment_code($code);
            }
        }
        else
            return $code;
    }

    public function login (LoginRequest $request){

        $role= $this->check_role($request->email);
        if($role==0)
            return response(['message'=>'Email not found'],500);
        if($request->remember_me== "true"){
            Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(7));
        }

        $authority=Authority::first(['stripe_price_id',]);


        $request=$request->toArray();
//        try{
        if($role == 1){

           if (Auth::guard('facilitator')->attempt(['email' => $request['email'], 'password' => $request['password']])){

               $facilitator=Facilitator::find(Auth::guard('facilitator')->user()->id);
               $facilitators_institution=Institution::find($facilitator->institution_id);
               if($facilitator->status == 0 || $facilitators_institution->status== 0)
                   return response(['message'=>'You are not Active, Please Contract Admin For Activation'],500);

               else if($facilitators_institution->stripe_id == null && $facilitators_institution->trial_ends_at < Carbon::now()){
                   return response(['message'=>'Trial Ended, Contract Institution Admin for Reactivation'],410);
               }

//               else if($facilitators_institution->stripe_id != null && $facilitators_institution->trial_ends_at < Carbon::now()){
//
//               $subscriptions_array =  $this->stripe->subscriptions->all([
//                   'customer' => $facilitators_institution->stripe_id,
//                   //'price'=>$authority->stripe_price_id,
//                   'status'=>'active'
//               ])->data;
//               $count_subscription=count($subscriptions_array);
//
//               if(count($subscriptions_array) <= 0 && $facilitators_institution->trial_ends_at < Carbon::now())
//                   return response(['message'=>'Trial Ended, Contract Institution Admin for Reactivation'],500);
//
//                   $flag="<<facilitator!";
//                   Cache::put($facilitator->id.'institution_subscribed'.$facilitator->email, $flag.$count_subscription.$flag, $seconds = 30*60);
//               }



               $access= Auth::guard('facilitator')->user()-> createToken('Token') ;
               $institution_name= Institution::find(Auth::guard('facilitator')->user()->institution_id)->institution_name;
               $accessToken=$access->accessToken;
               $token_id=$access->token->id;
               DB::table('oauth_access_tokens')->where('id','=',$token_id)->update(['role' => 1]);
                return response ([
                    'facilitator'=> Auth::guard('facilitator')->user(),
                    'token' => $accessToken,
                    'institution_name'=>$institution_name
                ]);
            }

            else {
                return response(['message' => 'Invalid Credentials' ],500);
            }
        }

        else if($role == 2){

            if (Auth::guard('student')->attempt(['email' => $request['email'], 'password' => $request['password']])){

                $student=Student::find(Auth::guard('student')->user()->id);
                $institution_name=Institution::find($student->institution_id)->institution_name;
                $students_institution=Institution::find($student->institution_id);
                if($student->status == 0 || $students_institution->status== 0)
                    return response(['message'=>'You are not Active, Please Contract Admin For Activation'],500);

                else if($students_institution->stripe_id == null && $students_institution->trial_ends_at < Carbon::now()){
                    return response(['message'=>'Trial Ended, It id time to make things official'],410);
                }

//                else if($students_institution->stripe_id != null && $students_institution->trial_ends_at < Carbon::now()){
//                $subscriptions_array =  $this->stripe->subscriptions->all([
//                    'customer' => $students_institution->stripe_id,
//                    //'price'=>$authority->stripe_price_id,
//                    'status'=>'active'
//                ])->data;
//
//                $count_subscription=count($subscriptions_array);
//
//                if(count($subscriptions_array) <= 0 && $students_institution->trial_ends_at < Carbon::now())
//                    return response(['message'=>'Trial Ended, Contract Institution Admin for Reactivation'],500);
//
//                $flag="<<student!";
//                Cache::put($student->id.'institution_subscribed'.$student->email, $flag.$count_subscription.$flag, $seconds = 30*60);
//                }

                $access = Auth::guard('student')->user() -> createToken('Token');
                $accessToken=$access->accessToken;
                $token_id=$access->token->id;
                DB::table('oauth_access_tokens')->where('id','=',$token_id)->update(['role' => 2]);
                return response ([
                    'student'=> Auth::guard('student')->user(),
                    'token' => $accessToken,
                    'institution_name'=>$institution_name
                ]);
            }

            else {
                return response(['message' => 'Invalid Credentials' ],500);
            }
        }

        else if($role == 3){
            if (Auth::guard('institution')->attempt(['email' => $request['email'], 'password' => $request['password']])){

                $institution=Institution::find( Auth::guard('institution')->user()->id);
                if($institution->status== 0)
                    return response(['message'=>'You are not Active, Please Contract Admin For Activation'],500);

//                $subscriptions_array =  $this->stripe->subscriptions->all([
//                    'customer' => $institution->stripe_id,
//                    'price'=>$authority->stripe_price_id,
//                    'status'=>'active'
//                ])->data;
//
//                if(count($subscriptions_array) <= 0 && $institution->trial_ends_at < Carbon::now())
//                    return response(['message'=>'Trial Ended, Contract Institution Admin for Reactivation'],500);
                if(Cache::has($institution->id.'institution_subscribed'.$institution->email))
                    Cache::forget($institution->id.'institution_subscribed'.$institution->email);
                if(Cache::has($institution->id.'bill_estimation'.$institution->email))
                    Cache::forget($institution->id.'bill_estimation'.$institution->email);

                $access = Auth::guard('institution')->user() -> createToken('Token');
                $accessToken=$access->accessToken;
                $token_id=$access->token->id;
                DB::table('oauth_access_tokens')->where('id','=',$token_id)->update(['role' => 3]);
                return response (['institution'=> Auth::guard('institution')->user(), 'token' => $accessToken]);
            }

            else {
                return response(['message' => 'Invalid Credentials' ],500);
            }
        }

        else if($role == 4){
            if (Auth::guard('authority')->attempt(['email' => $request['email'], 'password' => $request['password']])){
                $access = Auth::guard('authority')->user() -> createToken('Token');
                $accessToken=$access->accessToken;
                $token_id=$access->token->id;
                DB::table('oauth_access_tokens')->where('id','=',$token_id)->update(['role' => 4]);
                return response (['Authority'=> Auth::guard('authority')->user(), 'token' => $accessToken]);
            }

            else {
                return response(['message' => 'Invalid Credentials' ],500);
            }
        }

        if($request["remember_me"] == "true"){
            Passport::personalAccessTokensExpireIn(Carbon::now()->subDays(7));
        }
//        } catch (Throwable $e) {
//            report($e);
//            return response(['message' => 'Error Occurred' ],500);
//        }

    }


    public function email_verification_request(EmailVerificationRequest $request){
        $this->CookieController->flush_cookies();
        $email = $request->email;
        $random = Str::random(6);
        if($request->institution_url != null && $request->has('institution_url')){

            if(str_starts_with(env('APP_URL'), 'http://') && str_starts_with($request->institution_url, 'http://')){
                 $institution_code = Str::between($request->institution_url, 'http://', '.'.Str::after(env('APP_URL'),'http://'));
            }
            else if(str_starts_with(env('APP_URL'), 'https://') && str_starts_with($request->institution_url, 'https://')){
                $institution_code = Str::between($request->institution_url, 'https://', '.'.Str::after(env('APP_URL'),'https://'));
            }
            else if(str_starts_with(env('APP_URL'), 'https://') && str_starts_with($request->institution_url, 'http://')){
                $institution_code = Str::between($request->institution_url, 'http://', '.'.Str::after(env('APP_URL'),'https://'));
            }
            else if(str_starts_with(env('APP_URL'), 'http://') && str_starts_with($request->institution_url, 'https://')){
                $institution_code = Str::between($request->institution_url, 'https://', '.'.Str::after(env('APP_URL'),'http://'));
            }
            else if(!str_starts_with($request->institution_url, 'http://') || !str_starts_with($request->institution_url, 'https://'))
                $institution_code=$request->institution_url;

            setcookie('institution_code',$this->EncryptionController->encryption($institution_code ,'encrypt' ),time() + (600));
        }

        if($request->role == 2 || $request->role == 1 ){
            $request->validate([ 'institution_url'=>'required' ]);
            if(!Institution::where('institution_code',$institution_code)->exists())
                return response(['message'=>'Institution doesnt exists'],500);
        }

        try {
            $this->OTPController->RegistrationOTPSend($random, $email);
            setcookie('email',$this->EncryptionController->encryption($email ,'encrypt' ),time() + (600));
            setcookie('otp',$this->EncryptionController->encryption($random ,'encrypt' ),time() + (600));

            //OTP Email Send
            return response([
                'message' => 'Otp Send Successfully',
                'user' => $email,
                'otp' => $random,
]);

        } catch (\Exception $e) {
            report($e);
            return response(['message' => 'Error Occurred' ],500);
        }
    }

    public function email_verify(EmailVerifyRequest $request){

        $response= $this->CookieController->check_cookies_exists('otp');
        if($response) return $response;

        $email_verified_at = ['email_verified_at' => NOW()];
        try{
            $otp= $this->EncryptionController->encryption($_COOKIE['otp'],'decrypt');

            if ($otp == $request->otp) {

                $verified_email=$this->EncryptionController->encryption(($this->EncryptionController->encryption($_COOKIE['email'],'decrypt')),'verified_encrypt');
                setcookie('verified_email',$verified_email,time() + (600));
                return response([
                    'message' => 'Email Verified Successfully',
                    'email_verification' => $email_verified_at[ "email_verified_at"],

                ]);

            } else {

                return response(['message' => 'Invalid OTP'],500);
            }
        }
            catch (Throwable $e) {
                    report($e);
                    return response(['message' => 'Error Occurred' ],500);
                }
    }

    public function forget_password_otp_request(ForgetPasswordOTPRequest $request){

        $request->validate([
            'email'=> new ifExistsInDB()
        ]);

        $random = Str::random(6);
        $email=$request->email;
        $role= $this->check_role($email);
        try{
            $this->OTPController->ForgetPasswordOTPSend($random, $email);
            setcookie('forget_password_email',$this->EncryptionController->encryption($email ,'encrypt' ),time() + (600));
            setcookie('forget_password_role',$this->EncryptionController->encryption($role ,'encrypt' ),time() + (600));
            setcookie('forget_password_otp',$this->EncryptionController->encryption($random ,'encrypt' ),time() + (600));

            return response([
                'message'=>"OTP sent"
                //,$random
            ]);
        }catch (Throwable $e) {
            report($e);
            return response(['message' => 'Error Occurred'], 500);
        }

    }

    public function forget_password_otp_verify(OTPVerifyRequest $request){
    $response= $this->CookieController->check_cookies_exists('forget_password_otp');
    if($response) return $response;

    try{
        $otp= $this->EncryptionController->encryption($_COOKIE['forget_password_otp'],'decrypt');

        if ($otp == $request->otp) {
            $verified_email = $this->EncryptionController->encryption(($this->EncryptionController->encryption($_COOKIE['forget_password_email'],'decrypt')),'verified_encrypt');
            setcookie('forget_password_verified_email',$verified_email,time() + (600));
            return response([
                'message' => 'Email Verified Successfully',
            ]);

        } else {

            return response(['message' => 'Invalid OTP'],500);
        }
    }
    catch (Throwable $e) {
            report($e);
            return response(['message' => 'Error Occurred' ],500);
        }

    }

    public function forget_password_change_password(ForgetPasswordRequest $request){
        $response= $this->CookieController->check_cookies_exists('forget_password_verified_email');
        if($response) return $response;
        try{
            $email= $this->EncryptionController->encryption($_COOKIE['forget_password_verified_email'],'verified_decrypt');
            $role = $this->check_role($email);
            $user=null;
            if ($role==1){ $user= Facilitator::where('email','=',$email)->first(); }
            else if ($role==2){ $user= Student::where('email','=',$email)->first(); }
            else if ($role==3){ $user= Institution::where('email','=',$email)->first(); }
            $user->password= Hash::make($request->password);
            $user->save();
            $this->CookieController->flush_cookies();
            return response(['message'=>'password changed successfully']);
        }catch (Throwable $e) {
            report($e);
            return response(['message' => 'Error Occurred'], 500);
        }

    }


}
