@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-cogs"></i> {{ $pageTitle }}</h1>
        </div>
    </div>
    @include('admin.partials.flash')
    <div class="admin--category-create">
        <form action="{{ route('admin.blogs.store') }}" method="POST" role="form">
            @csrf
            <h3 class="tile-title">Blog Information</h3>
            <hr>
            <div class="row">
                <div class="col-12 col-md-6 mb-3 form-group">
                    <label class="control-label" for="code">Title</label>
                    <input class="form-control" type="text" placeholder="Enter blog code" id="title" name="title" value="{{ old('title') }}" />
                </div>

                <div class="col-12 col-md-6 mb-3 form-group">
                    <label class="control-label" for="frontend_type">Category</label>
                    @php $types = ['select' => 'Select Box', 'radio' => 'Radio Button', 'text' => 'Text Field', 'text_area' => 'Text Area']; @endphp
                    <select name="category_id" id="category_id" class="form-control">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group mb-3">
                <input type="checkbox" id="public" name="public" value="{{ old('public') }}" />
                <label class="control-label" for="public">Public</label>
            </div>

            <div class="row mt-2">
                <div class="col-12 text-end btn-group" role="group">
                    <a class="btn btn-secondary" href="{{ route('admin.blogs.index') }}"><i class="fa fa-fw fa-lg fa-chevron-left"></i>Return</a>
                    <button class="btn btn-dark" type="submit"><i class="fa fa-fw fa-lg fa-floppy-disk"></i>Save Changes</button>
                </div>
            </div>
        </form>
    </div>
@endsection