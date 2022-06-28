@extends('emails.email-layout')
@section('content')
    <div>
        <center>
            <div style="max-width: 665px;margin: 60px 0px;">
                <div style="background: #fff;border-radius: 5px 5px 0px 0px;">
                    <a href="#"><img style="margin: 33px 0px;" src="{{ asset('/mail-assets/mail-logo.png') }}" alt="">
                        <p>From:{{$name}}</p>
                        <p>Email:{{$email}}</p>
                        <p>Message:{{$message}}</p>
                    </a>
                </div>
            </div>
        </center>
    </div>
@endsection
