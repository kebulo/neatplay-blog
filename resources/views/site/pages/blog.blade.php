@extends('site.app')
@section('title', $blog->name)
@section('content')
    <section class="section-pagetop bg-dark">
        <div class="container clearfix">
            <h2 class="title-page">{{ $blog->title }}</h2>
        </div>
    </section>

    <section class="section-content bg padding-y border-top" id="site">
        <small>{{ $blog->publish_date }}</small>

        <div>
            {{ $blog->content }}
        </div>
    </section>
@stop