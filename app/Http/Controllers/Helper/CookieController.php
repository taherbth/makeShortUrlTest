<?php

namespace App\Http\Controllers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function cookie(){
        return $_COOKIE;
    }

    public function flush_cookies_except(){
        $past = time() - 3600;
        foreach ( $_COOKIE as $key => $value )
        {
            if ($key == "XSRF-TOKEN" ||$key == "laravel_session") continue;
            setcookie( $key,"", $past );
        }
    }

    public function flush_cookies(){
        $past = time() - 3600;
        foreach ( $_COOKIE as $key => $value )
        {
            if ($key == "email" || $key == "otp"|| $key == "role"|| $key == "verified_email" || $key == "forget_password_email" || $key == "forget_password_otp"|| $key == "forget_password_role"|| $key == "forget_password_verified_email") {
                setcookie( $key,"", $past );
            }

        }
    }
    public function check_cookies_exists($cookie_name){
        if(!isset ($_COOKIE[$cookie_name])){
            return response(['message'=>'Session Expired, Please request again'],500);
        }

    }
}
