<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>{{ config('app.name') }}</title>

  <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">

      <!-- Navbar -->
      @include('layouts.topnav')
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      @include('layouts.sidenav')
      <!-- /. Main Sidebar Container -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <div class="content">
            @yield('content')
        </div>
      </div>
      <!-- /.content-wrapper -->

      <!-- Main Footer -->
      @include('layouts.footer')
      <!-- /. main footer -->
    
  </div>
  <!-- ./wrapper -->
  
  <script src="{{ asset('js/app.js') }}"></script>

</body>
</html>
