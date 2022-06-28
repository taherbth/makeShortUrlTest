@extends('layouts.app_layout')
@section('banner-section')
    <div class="navbar-background"></div>
@endsection
@section('content')
    <section>
        <div class="section-wrapper--light">
            <div class="container">
                <h3 class="text-center mb-5">Our Terms and Conditions</h3>
                <div class="container">
                    <p class="text-center">{!! $data->value ?? '' !!}</p>
                </div>
            </div>
        </div>
    </section>

@endsection
