@extends('emails.email-layout')
@section('content')
    <div>
        <center>
            <div style="max-width: 665px;margin: 60px 0px;">
                <div style="background: #fff;border-radius: 5px 5px 0px 0px;">
                    <a href="#"><img style="margin: 33px 0px;" src="{{ asset('/mail-assets/mail-logo.png') }}" alt=""></a>
                </div>
                <div style="background: url('{{ asset('/mail-assets/mail-body-background.png') }}');padding: 100px 0px">
                    <h2 style="padding: 0;margin: 0;color: #fff;font-size: 40px;font-weight: 900;margin-bottom: 18px;text-align: center">Your payment <br> Invoice</h2>
                    <p style="padding: 0;margin: 0;color: #fff;font-size: 20px;font-weight: 500;text-align: center">We are very glad to have you onboard :)</p>
                </div>
                <div style="background: #fff;color: #909FA8;text-align: center;padding: 30px;border-bottom: 1px solid #EDEFF0;">
                    <p style="text-align: center;margin-bottom: 30px;">We receive your payment to see your invoice <br> Click belows button.</p>
                    <a style="text-decoration: none;background: #38B6FF;color: #fff;padding: 10px 20px;border-radius: 5px;font-weight: 600;" href="{{ $subscription_obj }}">View Invoice</a>
                </div>
                <div style="background: #fff;color: #909FA8;text-align: left;padding: 30px;border-radius: 0px 0px 5px 5px;">
                    <p>Confidentiality Notice: This e-mail communication and any attachments may contain confidential and privileged information for the use of the designated recipients named above. If you are not the intended recipient, you are hereby notified that you have received this communication in error and that any review, disclosure, dissemination, distribution or copying of its contents is prohibited. If you have received this communication in error, please notify me immediately by replying to this message and deleting it from your computer. Thank you.</p>
                    <p>All Rights Reserved | Powered by iQuantile LLC</p>
                </div>
            </div>
        </center>
    </div>
@endsection
