<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'My Base Project')</title>
    <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
</head>
<body>

<div class="container-fluid">
    @yield('content')
</div>

<script>
    var socketio_base = "{{Config::get('services.socketio.url')}}";
</script>
<script src="{{ elixir('js/all.js') }}"></script>
@yield('page_js')
</body>
</html>