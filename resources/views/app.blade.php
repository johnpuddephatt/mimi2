<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="refresh" content="86400">

        <title>{{ config('app.name', 'Joy of Languages') }} {{ app()->version() }}</title>

        <!-- Styles -->
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        {{-- <link href="{{ mix('css/vue.css') }}" rel="stylesheet"> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.2.45/css/materialdesignicons.css" integrity="sha256-mHVnxh+7TPhWR15Mw9aeGEXnvJo75EqKr/zwS4pGIak=" crossorigin="anonymous" />
        <!-- Scripts -->
        @routes
        <script defer src="{{ mix('js/app.js') }}"></script>
    </head>
    <body>
        @include('navigation')
        @inertia
        @include('footer')
    </body>
</html>
