<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD VICTOR</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('style')
</head>
<header id="header" class="header fixed-top">
    @yield('header')
</header>

<body>
    <div id="app">
        @yield('contenido')
    </div>
</body>
<footer id="footer" class="footer">
    @yield('footer')
    <script id="script" src="{{ asset('js/app.js') }}"></script>
    @yield('script')
</footer>

</html>
