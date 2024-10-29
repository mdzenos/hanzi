<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Trang chủ')</title>
    <link rel="stylesheet" href="{{ asset('css/sb-admin-2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.css') }}">
    <script src="{{ asset('vendor/jquery/jquery.js') }}"></script>
    <style>
        body {
            font-family: 'SimHei', 'KaiTi', 'FangSong';
            background-color: #cbcbcbd4;
            color: #000000;
            overflow-x: hidden;
        }

        .card-body {
            background-color: #cbcbcbd4 !important;
        }

        .card-item {
            text-align: center;
        }

        .navbar-search {
            width: 100% !important;
        }

        .bg-white {
            background-color: #4d7181 !important;
        }
    </style>
</head>

<body>
    @include('layouts.header')
    <main>
        @yield('content')
    </main>
    @include('layouts.footer')
    @stack('scripts')
</body>

</html>
