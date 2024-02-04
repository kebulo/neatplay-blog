@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-cogs"></i> {{ $pageTitle }}</h1>
    </div>
</div>
@include('admin.partials.flash')
<div class="admin--blog-edit">
    <form action="{{ route('admin.blogs.update') }}" method="POST" role="form" enctype="multipart/form-data">
        @csrf
        <h3 class="tile-title">Blog Information</h3>
        <hr />

        <input type="hidden" name="id" value="{{ $blog->id }}">

        <div class="form-group mb-3">
            <label class="control-label" for="title">Title</label>
            <input class="form-control" type="text" placeholder="Enter the blog title" id="title" name="title"
                value="{{ old('title', $blog->title) }}" />
        </div>

        <div class="row">
            <div class="col-12 col-md-6 form-group mb-3">
                <label class="control-label" for="frontend_type">Category</label>
                @php $types = ['select' => 'Select Box', 'radio' => 'Radio Button', 'text' => 'Text Field', 'text_area'
                => 'Text Area']; @endphp
                <select name="category_id" id="category_id" class="form-control">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-6 form-group mb-3">
                <label for="publication_date">Publication Date</label>
                <input class="form-control" type="date" placeholder="Enter the blog title" id="publication_date"
                    name="publication_date" value="{{ old('publication_date', $blog->publication_date) }}" />
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-8 col-md-10 form-group mb-3">
                <label for="image">Image</label>
                <input id="image" type="file" name="image" class="form-control"
                    accept="image/png, image/jpeg, image/jpg" />
            </div>

            <div class="col-12 col-sm-4 col-md-2 position-relative">
                <img class="img-fluid" src="{{ asset('storage/' . $blog->public_path) }}" alt="" />
            </div>
        </div>

        <div class="form-group mb-3">
            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" id="public" name="public" {{ $blog->public == 1 ?
                    'checked' : '' }}/>Public
                </label>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="content">Content</label>
            <textarea name="content" id="content" class="form-control" rows="10">{{ old('content', $blog->content) }}</textarea>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-end btn-group" role="group">
                <a class="btn btn-secondary" href="{{ route('admin.blogs.index') }}"><i
                        class="fa fa-fw fa-lg fa-chevron-left"></i>Return</a>
                <button class="btn btn-dark" type="submit"><i class="fa fa-fw fa-lg fa-floppy-disk"></i>Save
                    Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection

<script>
    $(document).ready(function () {
        $('#content').summernote({
            height: 300, // Set the height of the editor
            placeholder: 'Write your HTML here...',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['view', ['fullscreen', 'help']]
            ]
        });
    });
</script>