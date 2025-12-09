<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Helpdesk Ticketing System </title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('public/templates/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- AdminLTE Theme -->
    <link rel="stylesheet" href="{{ asset('public/templates/dist/css/adminlte.min.css') }}">

    <!-- Custom Styles -->
    <style>
        body, p, h1, h2, h3, h4, h5, h6, span, div, a, li {
            font-family: Arial, sans-serif !important;
        }
         html { font-size: 12px !important; }
    body.text-sm { font-size: 12px !important; }  /* override text-sm AdminLTE */
    @media(min-width:1400px) { html { font-size: 12px !important; } }

    
    </style>

    @yield('styles')
</head>
<body class="hold-transition sidebar-mini text-m">
<div class="wrapper">
    <!-- Navbar -->
    @include('layouts.components.navbar')

    <!-- Sidebar -->
    @include('layouts.components.sidebar')

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                @yield('header')
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<!-- jQuery -->
<script src="{{ asset('public/templates/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('public/templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('public/templates/dist/js/adminlte.min.js') }}"></script>

<!-- Custom Scripts -->
@yield('scripts')
@stack('scripts')
</body>
</html>
