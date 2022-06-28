@extends('emails.email-layout')
@section('content')
    <div>
        <center>
            <div style="max-width: 665px;margin: 60px 0px;">
                <div style="background: #fff;border-radius: 5px 5px 0px 0px;">
                    <a href="#"><img style="margin: 33px 0px;" src="{{ asset('/mail-assets/mail-logo.svg') }}" alt=""></a>
                </div>
                <div style="background: url('{{ asset('/mail-assets/mail-body-background.svg') }}');padding: 100px 0px">
                    <h2 style="padding: 0;margin: 0;color: #fff;font-size: 40px;font-weight: 900;margin-bottom: 18px;text-align: center">Join <br> WAM ACADEMY</h2>
                    <p style="padding: 0;margin: 0;color: #fff;font-size: 20px;font-weight: 500;text-align: center">A better way to provide mentorship awaits!</p>
                </div>
                <div style="background: #fff;color: #909FA8;text-align: center;padding: 30px;">
                    <p style="text-align: center;margin-bottom: 30px;">You have been invited to join WAM ACADEMY as a facilitator. You will be <br> able to provide impactful stories to students and measure their progress.</p>
                    <a style="text-decoration: none;background: #38B6FF;color: #fff;padding: 10px 20px;border-radius: 5px;font-weight: 600;" href="{{ url('/auth/registation?role=2&ins_id=') }}">Accept invitation</a>
                </div>
            </div>
        </center>
        <script>
            function copy_otp_code() {
                let copyText = document.getElementById("wam-otp");
                copyText.select();
                copyText.setSelectionRange(0, 99999)
                document.execCommand("copy");
            }
        </script>
    </div>
@endsection
