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

    // 圖片及時預覽
    // function handleFileUpload(obj, idx) {
    //     let file = obj.files[0]

    //     if (file) {
    //         var reader = new FileReader()

    //         reader.onload = function(e) {
    //             $('#file_image_' + idx).attr('src', e.target.result)
    //             $('#file_preview_' + idx).show()
    //             $('#file_none_' + idx).hide()
    //         }

    //         reader.readAsDataURL(file)
    //     } else {
    //         $('#file_preview_' + idx).hide()
    //         $('#file_none_' + idx).show()
    //     }
    // }

    // 圖片排定刪除
    // function handleFileDelete(idx) {
    //     if (confirm('確定要刪除嗎？')) {
    //         $('#file_image_' + idx).attr('src', '')
    //         $('#file_preview_' + idx).hide()
    //         $('#file_none_' + idx).show()
    //         $('#file_file_' + idx).val('')
    //         $('#file_will_delete_' + idx).val(1)
    //     }
    // }
    </script>
    @yield('script')
</body>
</html>
