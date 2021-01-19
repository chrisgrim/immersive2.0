<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.meta')
    </head>
    <body>
        @include('layouts.nav')
        <main>
            @yield('content')
        </main>
        @include('layouts.footer')
    </body>
</html>