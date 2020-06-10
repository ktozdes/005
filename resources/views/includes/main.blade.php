<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('includes.header')
    </head>
    <body>
        <div id="app" class="container">
            @include('includes.navigation')
            <div class="container">
                @yield('content')
            </div>
            @include('includes.footer')
        </div>
    </body>
</html>
