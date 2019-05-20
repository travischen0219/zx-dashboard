<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/morris/morris.css')}}" rel="stylesheet" type="text/css" />


    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{asset('assets/global/css/components.css')}}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{asset('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{asset('assets/layouts/layout/css/layout.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/layouts/layout/css/themes/darkblue.css')}}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{asset('assets/layouts/layout/css/custom.css')}}" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link rel="shortcut icon" href="favicon.ico" />
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    {{--  <link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />  --}}
    <style>
        body{
            background-color: white;
            width:98%;
        }
        a{
            text-decoration:none;
        }
        #sample_2 td{
            font-size: 16px;
            vertical-align:middle;
        }
        #sample_2 th{
            font-size: 16px;
            vertical-align:middle;
        }
        #functions_btn{
            text-align: center;
        }
        table thead{
            color:#fff;
            background-color: #248ff1;
        }
        #sample_2_filter input {
            width:300px !important;
        }

        #loader_m {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{asset('assets/apps/img/loader_icon.gif')}}") 50% 50% no-repeat rgb(249,249,249);
            background-size:120px 120px;
        }

    </style>
</head>
<body>



<div id="loader_m"></div>

<div class="page-content" >

    <div class="row">
        <div class="col-md-12" >
            <form role="form" action="{{ route('selectMaterial.search') }}" method="POST" id="search_from">
                {{ csrf_field() }}
                <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 56px;padding-top: 20px;">
                    <div class="col-md-5">
                        <div class="form-group">
                                <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 篩選分類 :</label>
                                <div class="col-md-9">
                                    <select class="form-control" style="font-size: 14px;" name="search_category" onchange="search();">

                                        <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                        @foreach($material_categories as $cate)
                                            <option value="{{$cate->code}}" {{ $search_code == $cate->code ? 'selected' : '' }}>[ {{$cate->code}} ] {{$cate->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            {{-- <button type="submit" class="btn" style="background-color: #248ff1;color:#fff;font-size: 14px;">篩選</button> --}}
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
             <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark" style="margin-left:20px;">
                    <a href="{{ route('createMaterial') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增物料</a>
                </div>
                <div class="tools"> </div>
            </div>

            <div class="portlet light">
                <div class="portlet-title">
                    @include('includes.messages')
                    <div class="caption font-dark">
                        <span style="color:red;">尚未指定 單位 之物料不顯示</span>
                    </div>
                    <div class="tools"> </div>
                </div>



                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="sample_2" >
                        <thead>
                            <tr>
                                <th style="width: 100px;">操 作</th>
                                <th>編 號</th>
                                <th>分 類</th>
                                <th>品 名</th>
                                <th>單 位</th>
                                <th>尺 寸</th>
                                <th>顏 色</th>
                                <th>庫 存</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($materials as $material)
                                @if(true)
                                <tr>
                                    <td align="center" id="functions_btn">
                                        <a href="javascript:;" class="btn blue btn-outline btn-sm"
                                        onclick="parent.setMaterial('{{$material->fullCode}}','{{$material->fullName}}','{{$material->buy}}',
                                                                    '{{$material->unit}}','{{$material->cost}}','{{$material->price}}',
                                                                    '{{$material->id}}','{{$material->material_unit_name->name}}','{{$material->warehouse > 0 ? $material->warehouse_name->code : ''}}',
                                                                    '{{$material->warehouse > 0 ? $material->warehouse : ''}}',
                                                                    '{{$material->cal_unit}}',
                                                                    '{{$material->cal_price > 0 ? $material->cal_price : 0}}',
                                                                    '{{ $material->stock }}');">選擇</a>
                                    </td>
                                    <td>{{$material->fullCode}}</td>
                                    <td>
                                        @if($material->material_categories_code == '')
                                            <span>未指派</span>
                                        @else
                                            [ {{$material->material_categories_code}} ] {{$material->material_category_name->name}}
                                        @endif
                                    </td>

                                    <td>{{$material->fullName}}</td>
                                    <td>{{$material->material_unit_name->name}}</td>
                                    <td>{{$material->size}}</td>
                                    <td>{{$material->color}}</td>
                                    <td>{{$material->stock}}</td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->

        </div>
    </div>
</div>




<!-- BEGIN CORE PLUGINS -->
<script src="{{asset('assets/global/plugins/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/js.cookie.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js')}}" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{asset('assets/global/plugins/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/morris/morris.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/morris/raphael-min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/counterup/jquery.waypoints.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/counterup/jquery.counterup.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/amcharts/amcharts.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/amcharts/serial.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/amcharts/pie.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/amcharts/radar.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/amcharts/themes/light.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/amcharts/themes/patterns.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/amcharts/themes/chalk.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/ammap/ammap.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/ammap/maps/js/worldLow.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/amcharts/amstockcharts/amstock.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/flot/jquery.flot.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/flot/jquery.flot.resize.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/flot/jquery.flot.categories.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="{{asset('assets/global/scripts/app.min.js')}}" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{asset('assets/pages/scripts/dashboard.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="{{asset('assets/layouts/layout/scripts/layout.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/layouts/layout/scripts/demo.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/layouts/global/scripts/quick-sidebar.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/layouts/global/scripts/quick-nav.min.js')}}" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{asset('assets/global/scripts/datatable.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{asset('assets/pages/scripts/table-datatables-buttons.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
$( document ).ready(function() {
    $('input[type="search"]').focus();

});
function search(){
    $("#search_from").submit();
}
$(window).load(function() {
    $("#loader_m").fadeOut("fast");
});
</script>




</body>
</html>





