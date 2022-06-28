@extends('layouts.app_layout')

@section('banner-section')
    <div class="navbar-background"></div>
@endsection
@section('content')
    <div class="section-wrapper--light">
        <div class="container">
            <h3 class="text-center mb-5">Frequently Asked Questions</h3>
            <div id="accordion" class="accordion">

                @forelse ($data as $key => $item)
                    <div class="wm-card-transparent mb-4 collapsed" data-toggle="collapse" href="#collapse-{{ $key }}">
                        <p class="wm-card-header">{{ $item->question }}</p>
                        <div id="collapse-{{ $key }}" class="collapse" data-parent="#accordion" >
                            <p class="text-muted">{!! $item->answer ?? '' !!}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-danger">FAQ not updated yet !</p>
                @endforelse

            </div>
        </div>
    </div>
@endsection
