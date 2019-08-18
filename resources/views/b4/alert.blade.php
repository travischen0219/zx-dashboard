@if (count($errors) > 0)
    @php
        $error_message = '';
        foreach ($errors->all() as $error) {
            $error_message .= "<div class=\"text-danger\">$error</div>";
        }
    @endphp
    <script>
        swalOption.type = "error"
        swalOption.title = '錯誤';
        swalOption.html = '{!! $error_message !!}';
        swal.fire(swalOption);
    </script>
@endif
