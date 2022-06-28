<?php

namespace App\Http\Controllers\Helper;
use App\Events\otp\ForgetPasswordOtpEvent;
use App\Events\otp\PasswordResetOtpEvent;
use App\Events\otp\RegistrationOtpEvent;
use App\Http\Controllers\Controller;
use App\Mail\Facilitator\Invitation\NewFacilitatorInvitationMail;
use App\Mail\Institution\Payment\PaymentSucessfulMail;
use App\Mail\OTP\EmailResetOTP;
use App\Mail\OTP\ForgetPasswordOTP;
use App\Mail\OTP\PasswordResetOTP;
use App\Mail\OTP\RegistrationOTP;
use App\Mail\Promotion\NewUserWelcomeMail;
use App\Mail\Student\Invitation\NewStudentInvitationMail;
use Illuminate\Support\Facades\Mail;

class OTPController extends Controller
{

    public function RegistrationOTPSend($random, $email){
        Mail::to($email)->queue(new registrationOTP($random));
    }
    public function ForgetPasswordOTPSend($random, $email){
        Mail::to($email)->queue(new ForgetPasswordOTP($random));
    }
    public function EmailResetOTPSend($random, $email){
        Mail::to($email)->queue(new EmailResetOTP($random));
    }
    public function NewUserWelcomeMailSend($email,$role,$institution_id,$name){
        Mail::to($email)->queue(new NewUserWelcomeMail($role,$institution_id,$name));
    }
    public function FacilitatorInvitationSend($emails,$institution_id){
        foreach ($emails as $email){
            Mail::to($email)->queue(new NewFacilitatorInvitationMail($institution_id, $email));
        }
    }

    public function StudentInvitationSend($emails,$institution_id){
        foreach ($emails as $email){
            Mail::to($email)->queue(new NewStudentInvitationMail($institution_id ,$email));
        }
    }

    public function institution_payment_sucessful_mail($email ,$subscription_obj){
        Mail::to($email)->queue(new PaymentSucessfulMail($subscription_obj));
    }
}
