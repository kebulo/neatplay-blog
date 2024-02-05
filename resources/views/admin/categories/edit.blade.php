@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-cogs"></i> {{ $pageTitle }}</h1>
    </div>
</div>
@include('admin.partials.flash')
<div class="admin--categories">
    <form action="{{ route('admin.categories.update') }}" method="POST" role="form">
        @csrf
        <h3 class="tile-title">Attribute Information</h3>
        <hr>
        <input type="hidden" name="id" value="{{ $category->id }}">
        
        <div class="row">
            <div class="col-12 col-md-6 mb-4 form-group">
                <label class="control-label" for="code">Name</label>
                <input class="form-control" type="text" placeholder="Enter category name" id="name" name="name" value="{{ old('name', $category->name) }}" />
            </div>

            <div class="col-12 col-md-6 mb-4 form-group">
                <label class="control-label" for="code">Icon</label>
                <input class="form-control" type="text" placeholder="Enter the icon" id="icon" name="icon" value="{{ old('icon', $category->icon) }}" />
            </div>

            <div class="col-12 mb-4">
                <small>You can check the available icons in the page [<a href="https://fontawesome.com/v4/icons/" target="_blank">FontAwesome</a>] Make sure you paste only the class name Ie. <b><i>fa fa-battery-quarter</i></b></small>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 col-sm-8 m-auto text-end btn-group" role="group">
                <a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}"><i class="fa fa-fw fa-lg fa-chevron-left"></i>Return</a>
                <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-floppy-disk"></i>Save Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection