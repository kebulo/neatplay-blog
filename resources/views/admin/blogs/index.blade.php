@extends('admin.app')
@section('title') {{ $pageTitle }} @endsection
@section('content')
    <div class="app-title mb-5">
        <div>
            <h1><i class="fa-solid fa-book-open"></i> {{ $pageTitle }}</h1>
            <p>{{ $subTitle }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 mb-3">
            <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary pull-right">Create Article</a>
        </div>
        <div class="col-12 col-sm-6 col-md-8 mb-3">
            <form action="{{ route('admin.blogs.index') }}">
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
                            <th> Code </th>
                            <th> Title </th>
                            <th class="text-center"> Publication Date </th>
                            <th class="text-center"> Is Public </th>
                            <th style="width:100px; min-width:100px;" class="text-center text-danger">
                                <i class="fa fa-bolt"> </i>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($blogs as $blog)
                                <tr>
                                    <td>{{ $blog->id }}</td>
                                    <td>{{ $blog->title }}</td>
                                    <td class="text-center">{{ $blog->publication_date }}</td>
                                    <td class="text-center">
                                        @if ($blog->public == 1)
                                            <span>Yes</span>
                                        @else
                                            <span>No</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            <a href="{{ route('admin.blogs.delete', $blog->id) }}" class="btn btn-sm btn-danger" data-confirm="Are you sure you want to delete this blog?"><i class="fa fa-trash"></i></a>
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

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this blog?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function () {
            var deleteLinks = $('a[data-confirm]');
            var confirmationModal = $('#confirmationModal');
            var confirmDeleteLink = $('#confirmDelete');

            deleteLinks.click(function (event) {
                event.preventDefault();

                var confirmMessage = $(this).data('confirm');
                confirmationModal.find('.modal-body').text(confirmMessage);

                confirmDeleteLink.attr('href', $(this).attr('href')); // Set the href for delete

                confirmationModal.modal('show');
            });

            confirmDeleteLink.click(function () {
                window.location.href = $(this).attr('href'); // Follow the link on confirmation
            });
        });
    </script>
@endsection