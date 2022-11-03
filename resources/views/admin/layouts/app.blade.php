<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>CTAE Guest House Management | Dashboard</title>

    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper" id="app">

        @include('admin.layouts.partials.nav')

        @include('admin.layouts.partials.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
        <!-- /.content-wrapper -->

        @include('admin.layouts.partials.footer')
    </div>
    <!-- ./wrapper -->

    <script>
        const user = @json($user);
        window.user = user;
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('script')
</body>

</html>