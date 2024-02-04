@extends('site.app')
@section('title', 'Homepage')

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .articles--container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        padding: 20px;
    }

    .article--card {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 20px;
    }

    @media (max-width: 767px) {
        .articles--container {
            grid-template-columns: 1fr;
        }
    }



    /* Blog cards */

    .articles--main-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        max-width: 1200px;
        margin-block: 2rem;
        gap: 2rem;
        margin: 0 auto;
    }

    img {
        max-width: 100%;
        display: block;
        object-fit: cover;
    }

    .card {
        display: flex;
        flex-direction: column;
        width: clamp(20rem, calc(20rem + 2vw), 22rem);
        overflow: hidden;
        box-shadow: 0 .1rem 1rem rgba(0, 0, 0, 0.1);
        border-radius: 1em;
        background: #ECE9E6;
        background: linear-gradient(to right, #FFFFFF, #ECE9E6);

    }

    .article--card__body {
        color: #222;
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }


    .tag {
        align-self: flex-start;
        padding: .25em .75em;
        border-radius: 1em;
        font-size: .75rem;
    }

    .tag+.tag {
        margin-left: .5em;
    }

    .tag-blue {
        background: #56CCF2;
        background: linear-gradient(to bottom, #2F80ED, #56CCF2);
        color: #fafafa;
    }

    .tag-brown {
        background: #D1913C;
        background: linear-gradient(to bottom, #FFD194, #D1913C);
        color: #fafafa;
    }

    .tag-red {
        background: #cb2d3e;
        background: linear-gradient(to bottom, #ef473a, #cb2d3e);
        color: #fafafa;
    }

    .article--card__body h4 {
        font-size: 1.5rem;
        text-transform: capitalize;
    }

    .article--card__footer {
        color: #222;
        display: flex;
        padding: 1rem;
        margin-top: auto;
    }

    .articles--separator {
        width: 10%;
        margin: 0 auto;
    }

    .user {
        display: flex;
        gap: .5rem;
    }

    .user__info>small {
        color: #666;
    }

    .card__image {
        height: 300px;
        margin-right: 1rem;
        flex-shrink: 0;
    }
</style>

<section class="section banner banner-section">
    <div class="container banner-column">
        <img class="banner-image" src="<?php echo asset('storage/background.png'); ?>" alt="banner" />
        <div class="banner-inner">
            <h1 class="heading-xl">The Unveiled Pixel: Navigating the Spectrum of Tech Marvels</h1>
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

@section('content')
<section class="section-pagetop bg-dark">
    <div class="clearfix text-center m-1 mb-2">
        <h2 class="title-page">Articles</h2>
        <hr class="articles--separator" />
    </div>
</section>

<div class="articles--main-container">
    @if($blogs->isNotEmpty())
        @foreach($blogs as $article)
            <div class="card">
                <a href="{{ route('site.pages.blog', [strtolower(str_replace(' ', '-', $article->title)), $article->id]) }}">
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
    <style>
        .articles--pagination {
            text-align: center;
        }

        .articles--pagination svg.w-5.h-5 {
            width: 10px;
        }
    </style>
    <div class="articles--pagination">
        {{ $blogs->links() }}
    </div>
</section>
@stop