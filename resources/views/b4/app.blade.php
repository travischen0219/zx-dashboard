<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | 真心蓮坊進銷存系統</title>
    <link rel="stylesheet" href="/css/app.css">
    @yield('css')
</head>
<body>
    @include('b4.sidebar')

    <div id="content" class="container-fluid">
        <div class="h3 pb-2 mt-4 mb-5 border-bottom">@yield('page-header')</div>
        @yield('content')
    </div>

    <script src="/js/app.js"></script>
    <script>
    const dtOptions = {
        dom: `
            <"float-left mt-1"l>
            <"float-right align-middle mb-2 ml-2"B>
            <"float-right mt-1"f>
            rt
            <"float-left"i>
            <"float-right"p>
            <"clearfix">
        `,
        'language': {
            "url": '/json/datatable.zh-tw.json'
        },
        'buttons': [
            { extend: 'colvis', text: '欄位篩選' }
        ]
    }

    const swalOption = {
        title: "",
        text: "",
        type: "",
        showCancelButton: false,
        confirmButtonText: '確定',
        closeOnConfirm: true
    }
    </script>
    @yield('script')
</body>
</html>
