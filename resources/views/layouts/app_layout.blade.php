<!DOCTYPE html>
<html lang="en">
<head>
    {{-- meta information --}}    
    @include('layouts.sitemeta')
    {{-- link favicon --}}
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/wam-academy-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets/favicon/favicon.ico') }}" />
    {{-- title and link assets --}}
    <title>We Are Marcus | Character Development for Every Student</title>
    <link rel="stylesheet" href="{{ asset('frontend/css/_main.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <style>
        .alert-main{
            position: fixed;
            top: 20px;
            right: 20px;
            max-width: 400px;
            z-index: 99999;
            padding: 0 !important;
            border: none;
        }
        .alert-inner{
            width: 100%;
            height: 100%;
            position: relative;
            padding: 10px;
            padding-right: 20px;
        }
        .alert-title{
            font-size: .9rem;
            margin-bottom: 0;
        }
        .alert-text{
            font-size: .8rem;
            margin-bottom: 0;
        }
        .alert-inner .close{
            position: absolute !important;
            top: 4px !important;
            right: 5px !important;
            font-size: 1rem;
            padding: 0 !important;
        }
        .alert-main li{
            font-size: .8rem;
        }
    </style>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-N8TFJYCEE4"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-N8TFJYCEE4');
    </script>
</head>
<body>
    <header>        
        <div class="navigation-wrap bg-transparent start-header start-style">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <nav class="navbar navbar-expand-md navbar-light">

                            <a class="navbar-brand" href="{{ url('/') }}">
                                <img class="logo-dark" src="{{ url('/frontend/images/dark-logo.png') }}" alt="">
                                <img class="logo-light" src="{{ url('/frontend/images/light-logo.png') }}" alt="">
                            </a>

                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav ml-auto py-4 py-md-0">
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 {{ Request::path() == 'about'? 'active':'' }}">
                                        <a class="nav-link" href="{{ url('/about') }}">About</a>
                                    </li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 {{ Request::path() == 'blog'? 'active':'' }}">
                                        <a class="nav-link" href="{{ url('/blog') }}">Case Studies</a>
                                    </li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 {{ Request::path() == 'faq'? 'active':'' }}">
                                        <a class="nav-link" href="{{ url('/faq') }}">FAQ</a>
                                    </li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 {{ Request::path() == 'contact'? 'active':'' }}">
                                        <a class="nav-link" href="#footercontract">Contact</a>
{{--                                        <a class="nav-link" href="{{ url('/contact') }}">Contact</a>--}}
                                    </li>
                                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                        <a href="{{ url('/auth/login') }}" class="btn navbar-button" >Sign In</a>
                                    </li>
                                </ul>
                            </div>

                        </nav>
                    </div>
                </div>
            </div>
        </div>
        @yield('banner-section')
    </header>

    @yield('content')

    <footer>
        <div class="section-wrapper--dark--p-top" id="footercontract">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 pr-lg-5">
                        <form action="/" method="post">
                            @csrf

                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input class="col-lg-12 wm-transparent-input" type="text" name="name" value="" placeholder="Your Name" required>

                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <input class="col-lg-12 wm-transparent-input" type="text" name="email" value="" placeholder="Your Email" required>

                            @error('message')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <textarea class="col-lg-12 wm-transparent-input"  name="message" rows="5" placeholder="Your Message" required></textarea>
                            <button type="submit"  class="btn wm-btn-transparent col-lg-12 mb-5">Send Message</button>
                        </form>
                    </div>
                    
                </div>

                <div class="pb-4 mt-5 text-center">
                    <p>Powered by We Are Marcus and <a href="https://www.iquantile.com" target="_blank">iQuantile</a>, WAM's interactive mentoring platform (WAM Academy) provides students access to the powerful stories of mentors and measures student character growth through their writing. WAM is aligned with leading social-emotional learning frameworks and mindfulness best practices. Our 'train the trainer' model is most helpful for districts with an interest in the positive network effect of engaging youth of color in inclusive conversations at school and in afterschool settings. With our method, we reach more students in less time across the country and internationally.</p>
                    <p>&copy; Copyright {{ date('Y') }} | We Are Marcus</p>
                </div>

            </div>
        </div>
    </footer>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('frontend/js/script.js') }}"></script>
    
    <script>
        @if(session()->has('success'))
        swal({
            text: "{{session()->get('success')}}",
            icon: "success",
            button: "Ok",
        });
        @endif
    </script>
</body>
</html>
