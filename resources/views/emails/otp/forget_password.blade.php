@extends('emails.email-layout')
@section('content')
    <div>
        <center>
            <div style="max-width: 665px;margin: 60px 0px;">
                <div style="background: #fff;border-radius: 5px 5px 0px 0px;">
                    <a href="{{ url('/') }}"><img style="margin: 33px 0px;" src="{{ asset('/mail-assets/mail-logo.png') }}" alt=""></a>
                </div>
                <div style="background: url('{{ asset('/mail-assets/mail-body-background.png') }}');padding: 100px 0px">
                    <h2 style="padding: 0;margin: 0;color: #fff;font-size: 40px;font-weight: 900;margin-bottom: 18px;text-align: center">Forgot your <br> Password?</h2>
                    <p style="padding: 0;margin: 0;color: #fff;font-size: 20px;font-weight: 500;text-align: center">Looks like you need to reset your password, we're here to help.</p>
                </div>
                <div style="padding: 50px;background: #fff;border-radius: 0px 0px 5px 5px;">
                    <p style="color: #909FA8;text-align: center">Enter this 6 digit code to your browser for verification</p>
                    <div style="margin-top: 20px;">
                        <input style="width: 100%;color: #234153;font-size: 16px;background: #F7F8F9;border: none !important;text-align: center;height: 56px;border-radius: 5px;box-sizing: border-box;" type="text" id="wam-otp" value="{{ $otp }}" readonly>
                    </div>
                </div>
            </div>
        </center>
    </div>
@endsection
