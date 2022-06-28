<?php

namespace App\Http\Controllers\Helper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EncryptionController extends Controller
{
    public function encryption($a,  $sta){
        $ciphering = "AES-256-CBC";
        $options = 0;
        $encryption_key = env('COOKIE_AES_ENCRYPT_KEY', 'default');
        $encryption_iv = env('COOKIE_AES_ENCRYPT_IV', 'default');
        $encryption_verified_key = env('COOKIE_AES_ENCRYPT_VERIFIED_KEY', 'default');
        $encryption_verified_iv = env('COOKIE_AES_ENCRYPT_VERIFIED_IV', 'default');


        if($sta=='encrypt')
            return openssl_encrypt($a, $ciphering, $encryption_key, $options, $encryption_iv);

        if($sta=='decrypt')
            return openssl_decrypt($a, $ciphering, $encryption_key, $options, $encryption_iv);

        else if($sta=='verified_encrypt')
            return openssl_encrypt($a, $ciphering, $encryption_verified_key, $options, $encryption_verified_iv);

        else if($sta=='verified_decrypt')
            return openssl_decrypt($a, $ciphering, $encryption_verified_key, $options, $encryption_verified_iv);


    }
}
