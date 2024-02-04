@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-tags"></i> {{ $pageTitle }}</h1>
            <p>{{ $subTitle }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary pull-right">Add Category</a>
        </div>
        
        <div class="col-12 col-sm-6 col-md-8 mb-3">
            <form action="{{ route('admin.categories.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search..." aria-label="Recipient's username" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit">Button</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('admin.partials.flash')
    <div class="row">
        <div class="col-md-12">
            <div class="tile">
                <div class="tile-body">
                    <table class="table table-hover table-bordered" id="sampleTable">
                        <thead>
                        <tr>
                            <th> ID </th>
                            <th> Name </th>
                            <th class="text-center"> Icon </th>
                            <th style="width:100px; min-width:100px;" class="text-center text-danger">
                                <i class="fa fa-bolt"> </i>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->name }}</td>
                                    <td>
                                        <i class="{{ $category->icon }}"></i>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            <a href="{{ route('admin.categories.delete', $category->id) }}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endpush