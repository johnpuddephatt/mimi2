<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="refresh" content="86400">

  <title>{{ config('app.name', 'Joy of Languages') }} {{ app()->version() }}</title>

  <!-- Styles -->
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  {{-- <link href="{{ mix('css/vue.css') }}" rel="stylesheet"> --}}
  {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/4.9.95/css/materialdesignicons.min.css" rel="stylesheet"> --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.2.45/css/materialdesignicons.css" integrity="sha256-mHVnxh+7TPhWR15Mw9aeGEXnvJo75EqKr/zwS4pGIak=" crossorigin="anonymous" />
  <!-- Scripts -->
  @routes

</head>

<body class="has-navbar-fixed-top">
  <div id="static">
    @include('navigation')
    <main>
      @yield('content')
    </main>
    @include('footer')
  </div>
</body>

</html>
