<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ mix('css/home.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
    <div id="app">
        <div class="container mt-3 ">
            <div class="text-center py-2">
                <a href="/login">
                    <img height="100px" src="/images/logo-dark.svg"/>
                </a>
            </div>
        </div>

        <main>
            @yield('content')
        </main>
        <footer class="text-center my-3 mb-5">
            <img src="/images/sysvale-logo.svg" />
        </footer>
    </div>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>
</html>
