@extends('layouts.app_layout')

@section('banner-section')
    <div class="navbar-background"></div>
    <div class="section-wrapper--dark">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-5">
                    <h3 class="mb-5">Offline</h3>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <section>
        <div class="section-wrapper--light">
            <div class="container">
                You Are Offline, Please Enable Internet Connection
            </div>
        </div>
    </section>
@endsection
