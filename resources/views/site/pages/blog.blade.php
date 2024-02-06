@extends('site.app')
@section('title', $blog->title)
@section('content')
<section class="section banner banner-section comments--main-container">
    <div class="container banner-column">
        @if($blog->public_path)
        <img class="banner-image" src="{{ asset('storage/' . $blog->public_path) }}" alt="{{$blog->title}}" />
        @else
        <img class="banner-image" src="<?php echo asset('storage/background.png'); ?>" alt="{{$blog->title}}" />
        @endif

        <div class="banner-inner">
            <h1 class="heading-lg">{{ $blog->title }}</h1>
        </div>
    </div>
</section>


<section class="container comments--main-container" id="site">
    <span class="tag tag-blue mb-1">{{ $blog->category->name }}</span>

    <div>
        {!! $blog->content !!}
    </div>

    <div class="mt-1">
        <h5>Jane Doe</h5>
        <small>{{ \Carbon\Carbon::parse($blog->publication_date)->format('F Y') }}</small>
    </div>
</section>



<section class="container comments--main-container">
    <div id="comment--main-container">
        @foreach($blog->comments as $comment)
        <hr class="separator" />
        <div class="comments--info-main mb-1">
            <img src="https://source.unsplash.com/600x400/?profile" class="comments--info-image" alt="" />
            <div>
                <h4>
                    {{ $comment->name ?? 'Anonymous' }}
                    <small class="comments--publication-date">
                        @if($comment->publication_date)
                            {{ \Carbon\Carbon::parse($comment->publication_date)->format('F j, Y, H:i') }}
                        @else
                            {{ \Carbon\Carbon::parse($comment->created_at)->format('F j, Y, H:i') }}
                        @endif
                    </small>
                </h4>
                <p>{{ $comment->content }}</p>
            </div>
        </div>

        @if(!auth()->guest() && auth()->user()->id === $blog->user_id)
        <div class="comments--delete-comment-container text-right">
            <a href="{{ route('admin.comments.delete', [$comment->id, $blog->id]) }}" 
            class="comments--delete-comment"
            id="comments--delete-comment">Delete Comment</a>
            @if(session('error'))
                <div class="alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        @endif
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


<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        let nameInput = $("#name");
        let contentInput = $("#content");
        let contentRemainingCharacters = $("#comment--remaing-characters");

        $('.comments--delete-comment').on('click', function(e) {
            e.preventDefault();

            if (confirm("Are you sure you want to delete this comment? This action cannot be undone.")) {
                window.location.href = $(this).attr('href');
            }
        });

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
                        nameInput.val("");
                        contentInput.val("");

                        let name = (response.data.name)?response.data.name:'Anonymous';

                        let responseDate = (response.data.publication_date)?response.data.publication_date:response.data.created_at;
                        const publicationDate = new Date(responseDate);
                        const formattedDate = publicationDate.toLocaleString('en-UK', {
                            month: 'long', // Full month name (e.g., "January")
                            year: 'numeric', // Four-digit year (e.g., "2023")
                            hour: 'numeric', // Hour (e.g., "12", "03")
                            minute: 'numeric', // Minute (e.g., "05")
                        }).replace(' at', ',');

                        let commentHtml = `<hr class="separator" />
                        <div class="comments--info-main mb-1">
                           <img src="https://source.unsplash.com/600x400/?profile" class="comments--info-image" alt="" />
                           <div>
                               <h4>`+name+` <small class="comments--publication-date">`+formattedDate+`</small></h4>
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
            }, 5000);
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
@stop