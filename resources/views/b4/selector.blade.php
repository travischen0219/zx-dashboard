<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/css/app.css?{{ env('APP_VERSION') }}">
    <style>
        * {
            font-family: "Microsoft JhengHei";
            box-sizing: border-box;
        }
        body {
            padding: 24px;
        }
        .d-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .dataTables_wrapper {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    @yield('content')

    <script src="/js/app.js?{{ env('APP_VERSION') }}"></script>
    @yield('script')
</body>
</html>
