<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  @php
    $addons = auth()->user()->member->company['addons'] ?? [];
  @endphp
<head>
  <title>@yield('title')</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <link href="https://api.fontshare.com/v2/css?f[]=satoshi@1&display=swap" rel="stylesheet">
  @foreach($addons as $addon)
    @if(isset($addon['styles']))
      @foreach($addon['styles'] as $style)
        <link href="{{ $style }}" rel="stylesheet">
      @endforeach
    @endif
  @endforeach
</head>
<body>
  <div id="app-vue-3">
  </div>
  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <script src="/js/app-vue-3.min.js" defer></script> 
  <!-- TODO: resolver essa questão da importação -->
  @foreach($addons as $addon)
    @if(isset($addon['scripts']))
      @foreach($addon['scripts'] as $script)
        <script src="{{ $script }}" defer></script>
      @endforeach
    @endif
  @endforeach
</body>
</html>
