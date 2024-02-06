@extends('site.app')
@section('title', 'The Unveiled Pixel - Homepage')
@section('meta_description', "Dive into 'The Unveiled Pixel,' an exploration of the digital realm's latest technological marvels. Discover the intersection of innovation and imagination in this ever-evolving landscape")
@section('content')
<section class="section banner banner-section">
    <div class="container banner-column">
        <img class="banner-image" src="<?php echo asset('storage/background.png'); ?>" alt="banner" />
        <div class="banner-inner">
            <h1 class="heading-lg">The Unveiled Pixel: Navigating the Spectrum of Tech Marvels</h1>
            <p class="paragraph">
                Embark on a journey through the digital realm with 'The Unveiled Pixel.' Delve into the latest tech
                wonders, unraveling the secrets of the ever-evolving landscape where innovation meets imagination.
            </p>
        </div>

        <div class="banner-links">
            <a href="#" title=""><i class="bx bxl-facebook"></i></a>
            <a href="#" title=""><i class="bx bxl-instagram"></i></a>
            <a href="#" title=""><i class="bx bxl-twitter"></i></a>
            <a href="#" title=""><i class="bx bxl-youtube"></i></a>
        </div>
    </div>
</section>

<section class="section-pagetop bg-dark">
    <div class="clearfix text-center m-1 mb-2">
        <h2 class="title-page">Articles</h2>
        <hr class="articles--separator" />
    </div>
</section>

<div class="articles--main-container">
    @if($blogs->isNotEmpty())
    @foreach($blogs as $article)
    @php
        $urlFriendlyText = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $article->title));
        $urlFriendlyText = trim($urlFriendlyText, '-');
    @endphp
    <div class="card">
        <a href="{{ route('site.pages.blog', [$urlFriendlyText, $article->id]) }}">
            <div class="card__header">
                @if($article->public_path)
                <img src="{{ asset('storage/' . $article->public_path) }}" alt="{{$article->title}}" class="card__image"
                    width="600">
                @else
                <img src="https://source.unsplash.com/600x400/?technology" alt="{{$article->title}}" class="card__image"
                    width="600" />
                @endif
            </div>

            <div class="article--card__body">
                <span class="tag tag-blue">{{ $article->category->name }}</span>
                <h4>{{ $article->title }}</h4>
                <p>{{mb_strimwidth(strip_tags($article->content), 0, 200)}}</p>
            </div>

            <div class="article--card__footer">
                <div class="user">
                    <div class="user__info">
                        <h5>Jane Doe</h5>
                        <small>{{ \Carbon\Carbon::parse($article->publication_date)->format('F Y') }}</small>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endforeach
    @else
    <div>
        <h5>There are no blogs available at the moment</h5>
    </div>
    @endif
</div>

<section class="articles--container" id="site">
    <div class="articles--pagination">
        {{ $blogs->links() }}
    </div>
</section>
@stop