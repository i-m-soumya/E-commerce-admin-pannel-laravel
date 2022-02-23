@if (Route::has('login'))
@auth
<script>
    window.location = "/home"

</script>
@else
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Grocerbee') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/flyinghub.min.css') }}">
</head>
<body class="antialiased">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Grocerbee</a>
        <ul class="navbar-nav ml-auto">

            <li class="nav-item active">
                <a class="nav-link" href="{{ route('login') }}">Log in</a>
            </li>

            {{-- @if (Route::has('register'))
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            </li>
            @endif --}}
            @endauth
            @endif
        </ul>
    </nav>
    <div class="row">
        <div class="container">
            <div class="col d-flex justify-content-center mt-5">
                <h1>WELCOME TO Grocerbee</h1>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flyinghub.js') }}"></script>
    <script src="{{ asset('assets/plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/demo.js') }}"></script>
    <script src="{{ asset('assets/dist/js/pages/flyinghub_dashboard.js') }}"></script>
</body>
</html>
