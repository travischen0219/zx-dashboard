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
        html, body {
            margin: 0;
            padding: 0;
        }
        body {
            background-color: lightgrey;
        }
        @media print {
            @page {
                size: A4 portrait;
                margin-left: 12mm;
                margin-right: 12mm;
                margin-top: 12mm;
                margin-bottom: 12mm;
            }
            .no-print, .no-print * {
                display: none !important;
            }
        }
        @media screen {
            body {
                padding-top: 50px;
            }

            .container {
                padding: 12mm;
                background-color: #fff;
            }
        }
        .d-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        table, th, td {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    @yield('content')

    <script src="/js/app.js?{{ env('APP_VERSION') }}"></script>
    <script src="{{ asset('assets/global/plugins/jquery-ui/datepicker-zh-TW.js') }}" type="text/javascript"></script>

    @yield('script')
</body>
</html>
