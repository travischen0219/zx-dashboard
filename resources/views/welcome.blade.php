<!DOCTYPE html>

<html lang="en">



    <head>
        <meta charset="utf-8" />
        <title>登入</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for " name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="{{asset('assets/global/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/global/plugins/select2/css/select2-bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{asset('assets/global/css/components.css')}}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{asset('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="{{asset('assets/pages/css/login.css')}}" rel="stylesheet" type="text/css" />
        {{--  <link href="{{asset('assets/apps/css/.css')}}" rel="stylesheet" type="text/css" />  --}}
        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{asset('assets/layouts/layout/css/layout.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('assets/layouts/layout/css/themes/darkblue.css')}}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{asset('assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="favicon.ico" />

        <style>
        a{
            text-decoration: none;
        }
        #code_img{
            margin-top:20px;
            display:block;
        }
        #code img {
            display:block;
            margin:auto;
            margin: 15px auto;
            width: 310px;
            height: 63px;
            border: 1px solid #ccc;
        }
        </style>
    </head>
    <!-- END HEAD -->

    <body class=" login">
        <!-- BEGIN LOGO -->
        <div class="logo">
            <a href="index.html">
                {{--  <img src="" alt="" />  --}}
            </a>
            <!--    <h2 style="color:white;">真心蓮坊進銷存系統</h2>-->
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">

            <div class="portlet box" >

                <div class="portlet-body">
                    <h3> 登 入</h3>

                    @include('includes.messages')

                    <div class="tabbable-line" >
                        <ul class="nav nav-tabs ">
                            <li class="active">
                                <a href="#tab_15_1" data-toggle="tab" id="user_code"> 帳號 </a>
                            </li>
                           <!-- <li>
                                <a href="#tab_15_2" data-toggle="tab" id="user_name"> 帳號 </a>
                            </li>-->

                        </ul>
                        <div class="tab-content" >
                            <div class="tab-pane active" id="tab_15_1">
                                <!-- BEGIN LOGIN FORM -->
                                 <form class="login-form" action="{{ route('login') }}" method="post" id="name_form">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="setType" value="name">
                                    <input type="hidden" name="sendCode">


                                    <div class="form-group">

                                        <label class="control-label visible-ie8 visible-ie9">帳號</label>
                                        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="帳號" name="username" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">密碼</label>
                                        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="密碼" name="password" /> </div>

                                </form>

                                <!-- END LOGIN FORM -->
                            </div>
                            <div class="tab-pane" id="tab_15_2">
                                <!-- BEGIN LOGIN FORM -->


                                <form class="login-form" action="{{ route('login') }}" method="post" id="code_form">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="setType" value="code">
                                    <input type="hidden" name="sendCode">

                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">員工編號</label>
                                        <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="員工編號" name="usercode" /> </div>
                                    <div class="form-group">
                                        <label class="control-label visible-ie8 visible-ie9">密碼</label>
                                        <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="密碼" name="password" /> </div>

                                </form>
                                <!-- END LOGIN FORM -->
                            </div>

                            @if(false)
                                {{-- <div id="code">
                                    <input type="text" class="form-control form-control-solid placeholder-no-fix" name="code" placeholder="請輸入下列驗證碼"/>
                                    <div id="code_img">
                                        <img src="{{url('code')}}" alt="" onclick="this.src='{{url('code')}}?'+Math.random()">
                                    </div>
                                </div>
                                <div>
                                    <span>點擊圖片可刷新驗證碼</span>
                                </div> --}}
                            @else
                            @endif
                            <div class="form-actions" >
                                <a href="javascript:;" class="btn uppercase" style="background-color:#36c6d3;color:white;font-size: 16px;" onclick="sendForm();">登 入</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>






        </div>
        {{--  <div class="copyright"> 2018 © . </div>  --}}
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script>
<script src="../assets/global/plugins/ie8.fix.min.js"></script>
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{asset('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="{{asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{asset('assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="{{asset('assets/pages/scripts/login.min.js')}}" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{asset('assets/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/layouts/layout/scripts/demo.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/layouts/global/scripts/quick-sidebar.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('assets/layouts/global/scripts/quick-nav.min.js')}}" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });
            })

// $(document).on("click",".addOne",function(){
//             function setLogin(){
//                 alert($(this).attr('settype'));
//             }

            var send_type = 'code';
            $('#user_code').on('click',function(){
                send_type = 'code';
            });
            $('#user_name').on('click',function(){
                send_type = 'name';
            });

            function sendForm(){
                var code = $("input[type=text][name=code]").val();

                $("input[type=hidden][name=sendCode]").val(code);
                if(send_type == 'code'){
                    document.getElementById('name_form').submit();
                } else if(send_type == 'name'){
                    document.getElementById('name_form').submit();
                }
            }
        </script>
    </body>

</html>
