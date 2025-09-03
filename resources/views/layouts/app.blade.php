<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'HRMIS')</title>
</head>
<body>
    <div class="container">
        @yield('content')
        @stack('scripts')
    </div>
</body>
</html>
