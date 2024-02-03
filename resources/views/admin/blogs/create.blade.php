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
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane active" id="general">
                    <div class="tile">
                        <form action="{{ route('admin.blogs.store') }}" method="POST" role="form">
                            @csrf
                            <h3 class="tile-title">Blog Information</h3>
                            <hr>

                            <div class="tile-body">
                                <div class="form-group">
                                    <label class="control-label" for="code">Title</label>
                                    <input
                                        class="form-control"
                                        type="text"
                                        placeholder="Enter blog code"
                                        id="title"
                                        name="title"
                                        value="{{ old('title') }}"
                                    />
                                </div>

                                <div class="form-group">
                                    <input
                                        type="checkbox"
                                        id="public"
                                        name="public"
                                        value="{{ old('public') }}"
                                    />
                                    <label class="control-label" for="public">Public</label>
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
                            </div>

                            <div class="tile-footer">
                                <div class="row d-print-none mt-2">
                                    <div class="col-12 text-right">
                                        <a class="btn btn-danger" href="{{ route('admin.blogs.index') }}">
                                            <i class="fa fa-fw fa-lg fa-arrow-left"></i> Go Back
                                        </a>
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Create Article</button>
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