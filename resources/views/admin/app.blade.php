<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/backend/css/main.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    @include('admin.partials.header')

    <div class="container-fluid p-0 row flex-nowrap">
        @include('admin.partials.sidebar')
        <main class="pr-5 pl-5 col py-3">
            @yield('content')
        </main>
    </div>

    <!--<script src="{{ asset('/backend/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('/backend/js/popper.min.js') }}"></script>
    <script src="{{ asset('/backend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/backend/js/main.js') }}"></script>
    <script src="{{ asset('/backend/js/plugins/pace.min.js') }}"></script>
    @stack('scripts')-->
</body>

</html>