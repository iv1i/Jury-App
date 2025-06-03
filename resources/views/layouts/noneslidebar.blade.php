<!doctype html>
<html class="" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('media/img/kolos-white.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('style/scss/Main.css') }}">
    @yield('css')
    <title>@yield('title')</title>
</head>
<body>

<div class="app-container">
    @yield('appcontent')
</div>
<script src="{{ asset('js/Other/TableStyle.js') }}"></script>
<script src="{{ asset('js/App/App.js') }}"></script>
<script src="{{ asset('js/Other/SwitchTheme.js') }}"></script>
    @yield('scripts')
</body>
</html>
