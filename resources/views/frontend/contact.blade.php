@extends('layouts.app_layout')

@section('banner-section')
    <div class="navbar-background"></div>
    <div class="section-wrapper--dark">
        <div class="container">
            <h3 class="text-center mb-5"></h3>
            <div class="row">
                <div class="col-lg-4">
                    <div class="text-center">
                        <img class="mb-4" src="{{ url('frontend/images/Icon feather-map-pin.svg') }}" alt="">
                        <p>{{ isset($data['address'])? $data['address'] : "" }}</p>
                    </div>
                </div>

                @if(isset($data['phone']))
                <div class="col-lg-4" >
                    <div class="text-center">
                        <img class="mb-4" src="{{ url('frontend/images/Icon awesome-phone.svg') }}" alt="">
                        <p>{!! isset($data['phone'])? str_replace(',', '</br>', $data['phone']) : "" !!}</p>
                    </div>
                </div>
                @endif

                <div class="col-lg-4">
                    <div class="text-center">
                        <img class="mb-4" src="{{ url('frontend/images/Icon ionic-ios-mail.svg') }}" alt="">
                        <p>{{ isset($data['email'])? $data['email'] : "" }}</p>
                    </div>
                </div>
</div>
</div>
</div>
@endsection
@section('content')
<section>
<iframe style="width: 100%" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d49171645.418738686!2d-69.52698268567279!3d41.217432312322046!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e671fdb38f5b8b%3A0xc0345272f10c1f6e!2sH%C3%B4tel%20de%20Ville!5e0!3m2!1sen!2sbd!4v1629177660858!5m2!1sen!2sbd" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</section>
@endsection
