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
                    <form action="{{ route('admin.categories.update') }}" method="POST" role="form">
                        @csrf
                        <h3 class="tile-title">Attribute Information</h3>
                        <hr>
                        <div class="tile-body">
                            <input type="hidden" name="id" value="{{ $category->id }}">
                            <div class="form-group">
                                    <label class="control-label" for="code">Name</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        placeholder="Enter category name"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $category->name) }}"
                                    />
                                </div>

                                <div class="form-group">
                                    <label class="control-label" for="code">Icon</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        placeholder="Enter the icon"
                                        id="icon"
                                        name="icon"
                                        value="{{ old('icon', $category->icon) }}"
                                    />
                                </div>
                        </div>

                        <div class="tile-footer">
                            <div class="row d-print-none mt-2">
                                <div class="col-12 text-right">
                                    <a class="btn btn-danger" href="{{ route('admin.categories.index') }}"><i
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