@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-cogs"></i> {{ $pageTitle }}</h1>
    </div>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-3">
        <div class="tile p-0">
            <ul class="nav flex-column nav-tabs user-tabs">
                <li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab">General</a></li>
                <li class="nav-item"><a class="nav-link" href="#values" data-toggle="tab">Attribute Values</a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane active" id="general">
                <div class="tile">
                    <form action="{{ route('admin.blogs.update') }}" method="POST" role="form" enctype="multipart/form-data">
                        @csrf
                        <h3 class="tile-title">Attribute Information</h3>
                        <hr>
                        <div class="tile-body">
                            <input type="hidden" name="id" value="{{ $blog->id }}">
                            <div class="form-group">
                                <label class="control-label" for="title">Title</label>
                                <input class="form-control" type="text" placeholder="Enter the blog title" id="title" name="title" value="{{ old('title', $blog->title) }}" />
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="frontend_type">Category</label>
                                @php $types = ['select' => 'Select Box', 'radio' => 'Radio Button', 'text' => 'Text Field', 'text_area' => 'Text Area']; @endphp
                                <select name="category" id="category" class="form-control">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" id="public" name="public" {{
                                            $blog->public == 1 ? 'checked' : '' }}/>Public
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="publish_date">Publish Date</label>
                                <input class="form-control" type="date" placeholder="Enter the blog title" id="publish_date" name="publish_date" value="{{ old('publish_date', $blog->publish_date) }}" />
                            </div>

                            <div class="form-group">
                                <label for="image">Image</label>
                                <input id="image" type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/jpg" />
                            </div>

                            <div>
                            <img src="{{ asset('storage/' . $blog->public_path) }}" alt="">
                            </div>

                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea name="content" id="content" class="form-control" rows="10" value="{{ old('content', $blog->content) }}"></textarea>
                            </div>
                        </div>

                        <div class="tile-footer">
                            <div class="row d-print-none mt-2">
                                <div class="col-12 text-right">
                                    <a class="btn btn-danger" href="{{ route('admin.blogs.index') }}"><i
                                            class="fa fa-fw fa-lg fa-arrow-left"></i>Return</a>
                                    <button class="btn btn-success" type="submit"><i
                                            class="fa fa-fw fa-lg fa-check-circle"></i>Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function () {
        $('#content').summernote({
            height: 300, // Set the height of the editor
            placeholder: 'Write your HTML here...',
            codemirror: { // Enable codemirror to show HTML code
                theme: 'monokai',
                lineNumbers: true,
                mode: 'text/html',
            },
        });
    });
</script>