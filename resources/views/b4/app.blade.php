<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | 真心蓮坊進銷存系統</title>
    <link rel="stylesheet" href="/css/app.css?{{ env('APP_VERSION') }}">
    @yield('css')
</head>
<body>
    <div id="loader"><i class="fas fa-cog fa-spin"></i></div>

    @include('b4.sidebar')

    <div id="content" class="container-fluid">
        <div id="page-header" class="h3 pb-2 mt-4 mb-5 border-bottom">@yield('page-header')</div>
        @yield('content')
    </div>

    <script src="/js/app.js?{{ env('APP_VERSION') }}"></script>
    <script src="{{ asset('assets/global/plugins/jquery-ui/datepicker-zh-TW.js') }}" type="text/javascript"></script>
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
        ],
        'order': []
    }

    const swalOption = {
        title: "",
        text: "",
        type: "",
        showCancelButton: false,
        confirmButtonText: '確定'
    }

    Vue.filter('number_format', function (value) {
        if (isNaN(value)) return 0
        value = Math.round(value * 100) / 100
        return value.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    })

    var number_format = Vue.filter('my-number_format')

    $(function () {
        $('form').submit(function () {
            $.busyLoadFull("show", {
                textPosition: "bottom",
                textMargin: "20px",
                background: "rgba(0, 0, 0, 0.70)",
                text: '資料送出中，請勿關閉或離開...'
            });

            return true
        })

        $("#loader").fadeOut("fast")
    })
    </script>
    @yield('script')
</body>
</html>
