<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Base App')</title>
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
</head>
<body>
@if (Auth::check())
    @include('nav')
@endif

<div class="container-fluid">
    @yield('content')
</div>

<script src="{{ elixir('js/all.js') }}"></script>
@include('sweet::alert')
@yield('page_js')
</body>
</html>