@extends('layouts.app_layout')

@section('banner-section')
    <video class="embed-responsive"
        src="https://wam-bucket.s3.us-east-2.amazonaws.com/app/website/assets/banner.mp4"
        loop="" autoplay="autoplay" playsinline="" muted=""
    ></video>
    <div class="banner-container d-mobile-none">
        <h1 class="mb-3">Revolutionize <br> Mentoring.</h1>
        <p class="text-secondary mb-5">Reaching more students in less time <br> for all teachable moments</p>
        <a href="{{ url('auth/registation') }}" class="btn wm-btn-primary-l">Start Your Free Trial!</a> <br>
    </div>
@endsection
@section('content')
    <section>
        <div class="section-wrapper--light d-desktop-none">
            <div class="container">
                <h2 class="mb-3">Revolutionize Mentoring.</h2>
                <p class="text-muted mb-5">Reaching more students in less time for all teachable moments</p>
                <a href="{{ url('auth/registation') }}" class="btn wm-btn-primary-l">Start Your Free Trial!</a> <br>
            </div>
        </div>
    </section>

    <section>
        <div class="section-wrapper--dark">
            <div class="container">
                <h3 class="mb-5">Features</h3>
                <div class="row align-items-center">
                    
                    <div class="col-lg-6">
                        <div class="text-center position-relative">
                            <img class="border-radius img-fluid" src="{{ url('frontend/images/wam-application-view.PNG') }}" alt="">
                            <a class="video-btn" href="#videoModal" data-toggle="modal" data-src="https://www.youtube.com/embed/sfkSyjej6ec">
                                <img style="width: 138px;" class="overlay-center" src="{{ url('frontend/images/play-button.gif') }}" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section-wrapper--light">
            <div class="container">
                <div class="row align-items-center mb-5">
                    <div class="col-lg-6">
                        <div class="position-relative">
                            <img class="img-fluid border-radius box-shadow" src="{{ url('frontend/images/about-us-video-preview.png') }}" alt="">
                            <a class="video-btn" href="#videoModal" data-toggle="modal" data-src="https://www.youtube.com/embed/sfkSyjej6ec">
                                <img style="width: 138px;" class="overlay-center" src="{{ url('frontend/images/play-button.gif') }}" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h3 class="mb-4">About WAM Academy</h3>
                        <p>{!! $title ?? '' !!}</p>
{{--                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut ero labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco poriti laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in .Lorem ipsum dolor sit amet, consectetur adipisicing elit. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>--}}
                        <a href="{{ url('/auth/registation') }}" class="btn wm-btn-transparent--theme-light">Sign Up</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="mb-4 mt-5">WAM Mentoring Effect Creates Long-term Impact on</h3>
                    </div>
                    <div class="col-lg-4 ">
                        <p class="text-big">20%</p>
                        <p class="text-muted">Reduced <span class="text-primary">Suspension Rates</span></p>
                    </div>
                    <div class="col-lg-4 ">
                        <p class="text-big">40%</p>
                        <p class="text-muted">Less Likely to <span class="text-primary">Skip Class or Be Traunt</span></p>
                    </div>
                    <div class="col-lg-4 ">
                        <p class="text-big">60%</p>
                        <p class="text-muted">Less Likely to <span class="text-primary">Engage in Substance Abuse</span></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
 

    <section>
        <div class="section-wrapper--dark">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <h2 class="">Get Access From Any Device!</h2>
                        <h4 class="mb-3">We made WAMAcademy responsive, so that you can get access to our service from any where, any device.</h4>
                        <a href="#" class="btn wm-btn-primary-l mb-3">Start Your Free Trial!</a> <br>
                    </div>
                    <div class="col-lg-7">
                        <img class="img-fluid" src="{{ url('frontend/images/mockedup.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <!-- 16:9 aspect ratio -->
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="" id="video"  allowscriptaccess="always" allow="autoplay"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal-dialog {
            max-width: 800px;
            margin: 30px auto;
        }
        .modal-body {
            position:relative;
            padding:0px;
        }
        .close {
            position:absolute;
            right:-30px;
            top:0;
            z-index:999;
            font-size:2rem;
            font-weight: normal;
            color:#fff;
            opacity:1;
        }
    </style>
@endsection
