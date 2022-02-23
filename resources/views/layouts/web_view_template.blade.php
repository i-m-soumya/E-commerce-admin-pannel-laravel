<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Grocerbee') }}</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" href="{{ asset('assets/dist/img/full_logo.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/flyinghub.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <script src="{{ asset('assets/dist/js/toastr.min.js') }}"></script>
    {{-- semantic ui cdns --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.7/semantic.min.css">
</head>

<body class="">
    <!-- brand -->
    <nav class="navbar navbar-light bg-light">
        {{-- <a href="/" class="navbar-brand">
            <img src="assets/dist/img/full_logo.png" alt="Grocerbee" class="elevation-3">
        </a> --}}
        <a href="/" class="navbar-brand">
            Grocerbee
        </a>
    </nav>
          @yield('content')
    <footer class="bg-light p-3 mt-4">
        <strong>Copyright &copy; 2021 <a href="https:\\www.fourous.com"> Fourous Technologies </a>.</strong> All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>

    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flyinghub.js') }}"></script>
    <script src="{{ asset('assets/dist/js/app.js') }}"></script>
    {{-- semantic ui cdns --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.7/semantic.min.js"></script>
</body>

</html>
