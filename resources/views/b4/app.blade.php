<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | 真心蓮坊進銷存系統</title>
    <link href="{{asset('assets/global/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/css/app.css">
    {{-- <link rel="stylesheet" href="/css/b4.css"> --}}
    @yield('css')
</head>
<body>
    @include('b4.sidebar')

    <div id="content" class="container-fluid">
        <div class="h3 pb-2 mt-4 mb-2 border-bottom">@yield('page-header')</div>
        @yield('content')
    </div>

    <script src="/js/app.js"></script>
    <script src="{{asset('assets/global/plugins/jquery-ui/jquery-ui.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/jquery-ui/datepicker-zh-TW.js')}}" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    @yield('script')
</body>
</html>
