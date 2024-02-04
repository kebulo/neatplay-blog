@extends('site.app')
@section('title', $blog->title)

<style>
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

    .form-container {
        margin: 0 auto;
        width: 80%;
    }

    .form-group {
        position: relative;
    }

    label {
        position: absolute;
        top: 8px;
        left: 10px;
        color: #888;
        font-size: 15px;
        transition: top 0.3s, font-size 0.3s;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
        background: #3a3a3b5e;
        color: white;
        transition: border-color 0.3s;
    }

    input:focus,
    textarea:focus {
        border-color: dodgerblue;
    }

    input:focus+label,
    textarea:focus+label,
    input.has-value+label,
    textarea.has-value+label {
        left: 0;
        top: -20px;
        font-size: 13px;
        color: dodgerblue;
    }

    /* Comments Section */
    .separator {
        border: 2px solid #222;
        margin: 2rem 0;
    }
    
    .comments--info-main {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }

    .comments--info-image {
        width: 100px;
        height: 100px;
        margin-right: 1rem;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .comments--info-main div {
        flex: 1;
    }

    .comments--info-main h4 {
        margin-bottom: 0.5rem;
    }

    .comments--info-main p {
        margin: 0;
    }

    .comments--publication-date {
        font-size: 12px;
        color: #888;
    }

    .comments--publication-date {
        font-weight: 100;
        font-size: 12px;
        color: #adadad;
    }

    #comment--error-message {
        display: none;
        background: #ff00008c;
        border: 1px solid red;
        padding: 5px;
        font-size: 14px;
        border-radius: 5px;
        margin: 1rem 0;
        text-align: center;
    }

    #comment--remaing-characters {
        float: right;
    }
</style>

<section class="section banner banner-section">
    <div class="container banner-column">
        @if($blog->public_path)
        <img class="banner-image" src="{{ asset('storage/' . $blog->public_path) }}" alt="{{$blog->title}}" />
        @else
        <img class="banner-image" src="<?php echo asset('storage/background.png'); ?>" alt="{{$blog->title}}" />
        @endif

        <div class="banner-inner">
            <h1 class="heading-xl">{{ $blog->title }}</h1>
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
<section class="container" id="site">
    <span class="tag tag-blue mb-1">{{ $blog->category->name }}</span>

    <div>
        {!! $blog->content !!}
    </div>

    <div class="mt-1">
        <h5>Jane Doe</h5>
        <small>{{ \Carbon\Carbon::parse($blog->publication_date)->format('F Y') }}</small>
    </div>
</section>



<section class="container">
    <div id="comment--main-container">
        @foreach($blog->comments as $comment)
        <hr class="separator" />
        <div class="comments--info-main mb-1">
            <img src="https://source.unsplash.com/600x400/?profile" class="comments--info-image" alt="" />
            <div>
                <h4>
                    {{ $comment->name ?? 'Anonymous' }}
                    <small class="comments--publication-date">{{ \Carbon\Carbon::parse($comment->created_at)->format('F Y, H:i')}}</small>
                </h4>
                <p>{{ $comment->content }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <hr class="separator" />

    <div class="text-center mb-1">
        <h3>Enjoying what you're reading? Sent your opinion</h3>
    </div>

    <form id="comments--form-creation" data-blog-id="{{ $blog->id }}">
        @csrf
        <div id="comment--error-message"></div>

        <div class="form-container">
            <div class="form-group mb-2">
                <input type="text" id="name" name="name" />
                <label for="name">Name</label>
            </div>

            <div class="form-group mb-2">
                <textarea name="content" id="content" rows="5" maxlength="300" required></textarea>
                <label for="content">Comment*</label> <br />
                <small id="comment--remaing-characters">Characters remaining: 300</small>
            </div>

            <div class="text-right">
                <button class="menu-block">Post Comment</button>
            </div>
        </div>
    </form>
</section>

@stop
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        let nameInput = $("#name");
        let contentInput = $("#content");
        let contentRemainingCharacters = $("#comment--remaing-characters");

        nameInput.on("focusout", function() {
            setClassHasValue(nameInput);
        });

        contentInput.on("focusout", function() {
            setClassHasValue(contentInput);
        });

        contentInput.on('keyup', function () {
            var maxLength = contentInput.attr("maxlength");
            var currentLength = contentInput.val().length;
            var charactersRemaining = maxLength - currentLength;

            contentRemainingCharacters.text('Characters remaining: ' + charactersRemaining);
        });


        $("#comments--form-creation").on("submit", function (event) {
            event.preventDefault();

            let errorMessage = "";
            let errorBox = $("#comment--error-message");

            let formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');

            let blogId = $(this).data('blog-id');
            formData.append('blog_id', blogId);

            $.ajax({
                url: "{{ route('site.comments.store') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.success === 200) {
                        console.log(response.data);
                        let commentHtml = `<hr class="separator" />
                        <div class="comments--info-main mb-1">
                           <img src="https://source.unsplash.com/600x400/?profile" class="comments--info-image" alt="" />
                           <div>
                               <h4>`+response.data.name+` <small class="comments--publication-date">{{ \Carbon\Carbon::parse(`+response.data.created_at+`)->format('F Y, H:i')}}</small></h4>
                               <p>`+response.data.content+`</p>
                           </div>
                        </div>`;

                        $("#comment--main-container").append(commentHtml);
                    } else {
                        errorMessage = "There was an error publishing your message, make sure the content has data and doesn't exceed the 300 character limit";
                        errorBox.html(errorMessage);
                        errorBox.css({"display": "block"});
                    }
                },
                error: function (xhr, status, error) {
                    errorMessage = "There was an error publishing your message, make sure the content has data and doesn't exceed the 300 character limit";
                    errorBox.html(errorMessage);
                    errorBox.css({"display": "block"});
                }
            });

            setTimeout(() => {
                errorBox.html("");
                errorBox.css({"display": "none"});
            }, 3000);
        });
    });

    function setClassHasValue(input) {
        let nameValue = input.val();

        if (nameValue) {
            input.addClass('has-value');
        } else {
            input.removeClass('has-value');
        }
    }
</script>