@extends('layouts.app_layout')

@section('banner-section')
    <div class="navbar-background"></div>
    <div style="height: 120vh; overflow: hidden">
        <img style="width: 100%" class="img-fluid" src="{{ $data->banner ?? '' }}" alt="">
    </div>
@endsection
@section('content')
    <section>
        <div class="section-wrapper--light--p-bottom mt-5">
            <div class="container">
                <h3 class="mb-5">{{ $data->title ?? '' }}</h3>
                <div class="float-right d-mobile-none">
                    <ul class="left-sidebar">
                        <li><h3>Other Blogs</h3></li>
                        @forelse ($sidebar_blog as $item)
                            <li>
                                <div class="blog-item">
                                    <div class="img-container">
                                        <img class="img-fluid" src="{{ $item->banner }}" alt="">
                                    </div>
                                    <a href="{{ url('/blog/' . $item->id) }}">{{ $item->title }}</a>
                                </div>
                            </li>
                        @empty
                            <p class="text-danger">There have only one Blog !</p>
                        @endforelse
                    </ul>
                </div>
                <p>{!! $data->article ?? '' !!}</p>
            </div>
        </div>
    </section>

    <section>
        <div class="section-wrapper--light--p-bottom clear-both">
            <div class="container">
                <h3 class="text-center mb-5">Other Blogs</h3>
                <div class="row all-blog-list">
                    @forelse ($all_blog as $item)
                        <div class="col-lg-4 mb-5 ">
                            <div class="blog-item">
                                <div class="img-container">
                                    <img class="img-fluid" src="{{ $item->banner }}" alt="">
                                </div>
                                <a href="{{ url('/blog/' . $item->id) }}">{{ $item->title }}</a>
                            </div>
                        </div>
                    @empty
                        <p class="col-lg-12 text-center text-danger">There have only one Blog !</p>
                    @endforelse
                </div>
                <div class="text-center">
                    <button class="btn wm-btn-transparent--theme-light" onclick="loadMoreData()">Load More</button>
                </div>
            </div>
        </div>
    </section>

    <script>
        let data = @json($all_blog);
        var page = 2;

        async function loadMoreData (){
            fetch('/blog-list/{{ $data->id ?? "null" }}?page=' + page).then(res => res.json()).then(res => {
                page++
                data = data.concat(res.data);
                allBlogs = "";

                data.forEach(el => {
                    allBlogs += `
                        <div class="col-lg-4 mb-5">
                            <div class="blog-item">
                                <div class="img-container">
                                    <img class="img-fluid" src="${el.banner}" alt="">
                                </div>
                                <a href="/blog/${el.id}">${el.title}</a>
                            </div>
                        </div>
                    `
                });

                document.querySelector('.all-blog-list').innerHTML = allBlogs;
            })
        }
    </script>
@endsection
