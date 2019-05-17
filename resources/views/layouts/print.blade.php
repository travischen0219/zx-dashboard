<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link href="{{asset('assets/global/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
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

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <script src="{{asset('assets/global/plugins/jquery-ui/jquery-ui.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/jquery-ui/datepicker-zh-TW.js')}}" type="text/javascript"></script>

    @yield('script')
</body>
</html>
