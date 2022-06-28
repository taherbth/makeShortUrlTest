@extends('layouts.app_layout')

@section('banner-section')
    <div class="navbar-background"></div>
    <div class="section-wrapper--dark">
        <div class="container">
{{--            <div class="row align-items-center mb-5">--}}
{{--            <div class="row align-items-center">--}}
{{--                <div class="col-lg-7">--}}
{{--                    <div>--}}
{{--                        <img class="img-fluid border-radius box-shadow" src="{{ url('frontend/images/about-us-video-preview.png') }}" alt="">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-lg-5">--}}
{{--                    <h3 class="mb-5">About Us</h3>--}}
{{--                    <p class="text-center">"We decided to name our solution tied directly to the problem we’ve found across the country in our research. Marcus in We Are Marcus (WAM) is the name for one of the 24 million youth growing up in a fatherless household in the US. Marcus is also the name for the 16 million missing mentors who want to make time to positively impact a young person, but struggle to find that time. According to the National Mentoring Partnership, the “mentoring gap” continues to prevail.  Our mentor tech is gender-inclusive, but we decided to connect this story with this allegory and build a community around our shared experiences. Hence the name, We Are Marcus."- CEO, Christopher C. King, MPA, MBA</p>--}}
                    <p class="text-center">{!! \App\Models\Contact::where('attribute' , "abouttext")->first()->value ?? '' !!}</p>
{{--                    <a href="{{ url('/auth/registation') }}" class="btn wm-btn-transparent">Sign Up</a>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection
@section('content')
    <section>
        <div class="section-wrapper--light">
            <div class="container">
                <h3 class="text-center mb-5">Our Team</h3>
                <div class="row p-5">
                    @foreach(\App\Models\Member::all() as $member)
                        @if($member->is_admin==1)
                            <div class="col-lg-4 my-4" style="transform: scale(1.2)">
                                <div class="user-card employee">
                                    <img class="img-fluid w-100 border-radius" src="{{ $member->image }}" alt="">
                                    <div class="user-about">
                                        <p class="user-name">{{$member->name}}</p>
                                        <p class="user-designation">{{$member->role}}</p>
                                        <ul class="user-social-info">
                                            @if( $member->linkedin != null)
                                                <li><a href="{{$member->linkedin}}"><img src="{{ url('frontend/images/Icon awesome-linkedin-d.svg') }}" alt=""></a></li>
                                            @endif
                                            @if($member->facebook != null)
                                                <li><a href="{{$member->facebook}}"><img src="{{ url('frontend/images/Icon awesome-facebook-d.svg') }}" alt=""></a></li>
                                            @endif
                                            @if($member->instagram != null)
                                                <li><a href="{{$member->instagram}}"><img src="{{ url('frontend/images/Icon awesome-instagram-d.svg') }}" alt=""></a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @else
                    <div class="col-lg-4 my-4">
                        <div class="user-card employee">
                            <img class="img-fluid w-100 border-radius" src="{{ $member->image }}" alt="">
                            <div class="user-about">
                                <p class="user-name">{{$member->name}}</p>
                                <p class="user-designation">{{$member->role}}</p>
                                <ul class="user-social-info">
                                    @if( $member->linkedin != null)
                                        <li><a href="{{$member->linkedin}}"><img src="{{ url('frontend/images/Icon awesome-linkedin-d.svg') }}" alt=""></a></li>
                                    @endif
                                    @if($member->facebook != null)
                                        <li><a href="{{$member->facebook}}"><img src="{{ url('frontend/images/Icon awesome-facebook-d.svg') }}" alt=""></a></li>
                                    @endif
                                    @if($member->instagram != null)
                                        <li><a href="{{$member->instagram}}"><img src="{{ url('frontend/images/Icon awesome-instagram-d.svg') }}" alt=""></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                        @endif
{{--                        For Admin--}}
{{--                        {{$member->is_admin}}--}}
{{--                        @if($member->is_admin == 1)--}}
{{--                        <div class="col-lg-4">--}}
{{--                            <div class="user-card employee" style="transform: scale(1)">--}}
{{--                                <img class="img-fluid w-100 border-radius" src="{{ $member->image }}" alt="">--}}
{{--                                <div class="user-about">--}}
{{--                                    <p class="user-name">Tipu Sultan Eiko</p>--}}
{{--                                    <p class="user-designation">Admin</p>--}}
{{--                                    <ul class="user-social-info">--}}
{{--                                        @if( $member->linkedin != null)--}}
{{--                                            <li><a href="{{$member->linkedin}}"><img src="{{ url('frontend/images/Icon awesome-linkedin-d.svg') }}" alt=""></a></li>--}}
{{--                                        @endif--}}
{{--                                        @if($member->facebook != null)--}}
{{--                                            <li><a href="{{$member->facebook}}"><img src="{{ url('frontend/images/Icon awesome-facebook-d.svg') }}" alt=""></a></li>--}}
{{--                                        @endif--}}
{{--                                        @if($member->instagram != null)--}}
{{--                                            <li><a href="{{$member->instagram}}"><img src="{{ url('frontend/images/Icon awesome-instagram-d.svg') }}" alt=""></a></li>--}}
{{--                                        @endif--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        @endif--}}
                    @endforeach
{{--                    <div class="col-lg-4">--}}
{{--                        <div class="user-card">--}}
{{--                            <img class="img-fluid w-100 border-radius" src="{{ url('frontend/images/kistofer.png') }}" alt="">--}}
{{--                            <div class="user-about">--}}
{{--                                <p class="user-name">Amber Jones</p>--}}
{{--                                <p class="user-designation">MARKETING LEAD</p>--}}
{{--                                <ul class="user-social-info">--}}
{{--                                    <li><img src="{{ url('frontend/images/Icon awesome-linkedin-d.svg') }}" alt=""></li>--}}
{{--                                    <li><img src="{{ url('frontend/images/Icon awesome-facebook-d.svg') }}" alt=""></li>--}}
{{--                                    <li><img src="{{ url('frontend/images/Icon awesome-instagram-d.svg') }}" alt=""></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-4">--}}
{{--                        <div class="user-card employee">--}}
{{--                            <img class="img-fluid w-100 border-radius" src="{{ url('frontend/images/md-asad.png') }}" alt="">--}}
{{--                            <div class="user-about">--}}
{{--                                <p class="user-name">Amber Jones</p>--}}
{{--                                <p class="user-designation">MARKETING LEAD</p>--}}
{{--                                <ul class="user-social-info">--}}
{{--                                    <li><img src="{{ url('frontend/images/Icon awesome-linkedin-d.svg') }}" alt=""></li>--}}
{{--                                    <li><img src="{{ url('frontend/images/Icon awesome-facebook-d.svg') }}" alt=""></li>--}}
{{--                                    <li><img src="{{ url('frontend/images/Icon awesome-instagram-d.svg') }}" alt=""></li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="section-wrapper--light--p-bottom">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 pr-5">
                        <img class="img-fluid wm-border-radius" style="height: 500px;" src="{{ \App\Models\Member::where('is_admin',1)->first()->image }}" alt="">
{{--                        <img class="img-fluid wm-border-radius" src="{{ url('frontend/images/kistofer-1.png') }}" alt="">--}}
                    </div>
                    <div class="col-lg-6 pl-5">
                        <p>{{\App\Models\Member::where('is_admin',1)->first()->admin_message}}</p>
{{--                        <p>Christopher King is our Founder and CEO. He is most proud of his work in mentoring and education. </p><p> Prior to We Are Marcus, he was a highly recognized teacher in Beford Stuyvesant, Brooklyn, a contract manager on behalf of Barack Obama's 2012 presidential campaign in Virginia, and later an education consultant in multiple states. </p><p> He has been featured in Black Enterprise and On The Ryse magazines, as a social innovation leader. Although he prides himself much more on being a great brother, son, and role model for youth.</p>--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
