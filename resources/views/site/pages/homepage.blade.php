@extends('site.app')
@section('title', 'Homepage')

@section('content')
    <section class="section-pagetop bg-dark">
        <div class="container clearfix">
            <h2 class="title-page">Articles</h2>
        </div>
    </section>

    <section class="section-content bg padding-y border-top" id="site">
    @foreach($blogs as $blog)
        <div class="blog--card">
            <a href="{{ route('site.pages.blog', [strtolower(str_replace(' ', '-', $blog->title)), $blog->id]) }}">
                <h3>{{ $blog->title }}</h3>
            </a>
        </div>
    @endforeach
    </section>
@stop