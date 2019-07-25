<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>登入 | 真心蓮坊進銷存系統</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="真心蓮坊進銷存系統" name="description" />
    <meta content="4family" name="author" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link href="{{asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/css/components.css')}}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{asset('assets/pages/css/login.css')}}" rel="stylesheet" type="text/css" />
</head>

<body class="login">
    <div class="content">
        <div class="portlet box" style="margin-bottom: 0;">
            <div class="portlet-body">
                <h3>真心蓮坊進銷存系統</h3>
                @include('includes.messages')
                <div class="tabbable-line">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_15_1">
                            <form class="login-form" action="{{ route('login') }}" method="post" id="name_form" target="_top">
                                {{ csrf_field() }}
                                <input type="hidden" name="setType" value="name">
                                <input type="hidden" name="sendCode">

                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">帳號</label>
                                    <input class="form-control form-control-solid placeholder-no-fix" type="text"
                                        autocomplete="off" placeholder="帳號" name="username" />
                                </div>
                                <div class="form-group">
                                    <label class="control-label visible-ie8 visible-ie9">密碼</label>
                                    <input class="form-control form-control-solid placeholder-no-fix" type="password"
                                        autocomplete="off" placeholder="密碼" name="password" />
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="form-actions text-center">
                        <a href="javascript: void(0);" class="btn btn-default uppercase"
                            style="background-color:#36c6d3;color:white;font-size: 16px;" onclick="sendForm();">登 入</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright"> 版本：0.90 </div>
    <script src="{{asset('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script>
        function sendForm() {
            document.getElementById('name_form').submit();
        }
    </script>
</body>

</html>
