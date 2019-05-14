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
    <link href="{{asset('assets/apps/css/style.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />

    <!-- END PAGE LEVEL PLUGINS -->
    <style>
    body{
        background-color: white;
        width:98%;            
    }
    /* 初始label顏色 */
    .form-group.form-md-line-input.form-md-floating-label .form-control ~ label {
        color: #248ff1; }
    /* help-block顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .help-block, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .help-block {
        color: #248ff1;}
    /* focus後的label顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus {
        color: #248ff1; }
    /* focus後的底線顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus:after, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus:after {
        background: #248ff1; }

    

    .form-group.form-md-line-input .form-control::-moz-placeholder {
      color: #248ff1;}
    .form-group.form-md-line-input .form-control:-ms-input-placeholder {
      color: #248ff1; }
    .form-group.form-md-line-input .form-control::-webkit-input-placeholder {
      color: #248ff1; }

    .form-horizontal .form-group.form-md-line-input > label {
    
    color: #248ff1;
    }

    button[type=submit]{
        color:#fff;
        background-color: #248ff1;
    }

    .table_title{
        font-size: 16px;
        line-height: 70px;
        color:#248ff1;
        text-align: center;
    }

    .material_code{
        font-size: 16px;
        line-height: 70px;
        color:#000;
        text-align: center;
        width:50px;
    }

    .table_content{
        font-size: 16px;
        line-height: 70px;
        color:#000;
        text-align: left;
    }

    a{text-decoration: none !important;}
    a:hover {text-decoration: none !important;}
    .info-box .action {
        text-align: right;
    }
    .info-box .content .text {
        margin: 0;
        font-size: 16px;
        position: absolute;
        top: 8px;
        right: 12px;
    }
    .info-box .content .text a + a {
        margin-left: 2px;
    }
    .info-box .content .number {
        margin-top: 5px;
        overflow: hidden;
        width: 260px;
    }
    .info-box .content .title {
        font-size: 18px;
    }
    .info-box .text a{
        color:red;
    }
</style>

</head>
<body class="page-header-fixed page-sidebar-fixed page-sidebar-closed-hide-logo page-content-white">
    

<!-- BEGIN PAGE BAR -->
<div class="page-content">

 

<div class="row">
    <div class="col-md-12 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light">
            @include('includes.messages')

            <div class="portlet-body form">
                <form role="form" action="{{ route('storeMaterial') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="col-md-10">

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span ><span style="color:red;">*</span>分類 : </span>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="material_category" name="material_category" onchange="showFullCode();">
                                            <option value="" {{ old('material_category') == '' ? 'selected' : '' }}>請選擇</option>
                                            @foreach($material_categories as $cate)
                                                <option value="{{$cate->code}}" {{ old('material_category') == $cate->code ? 'selected' : '' }}>[ {{$cate->code}} ] {{$cate->name}} </option>
                                            @endforeach
                                        </select>
                                        <label for="material_category" style="color:#248ff1;"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">                       
                                <div class="col-md-2 table_title">
                                    <span ><span style="color:red;">*</span>物料編號 : </span>
                                </div>
                                
                                <div class="col-md-2">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="code_1" class="form-control" id="code_1" onkeyup="showFullCode();" onchange="showFullCode();" value="{{ old('code_1') }}">
                                        <label for="code_1">選填</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="col-md-1 material_code">
                                    <span><b>-</b></span>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="code_2" class="form-control" id="code_2" onkeyup="showFullCode();" onchange="showFullCode();" value="{{ old('code_2') }}">
                                        <label for="code_2"></label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="col-md-1 material_code">
                                    <span><b>-</b></span>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="code_3" class="form-control" id="code_3" onkeyup="showFullCode();" onchange="showFullCode();" value="{{ old('code_3') }}">
                                        <label for="code_3">選填</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">                        
                                <div class="col-md-2 table_title">
                                    <span >完整編號 : </span>
                                </div>
                                <div class="col-md-5 table_content">
                                    <span id="fullCode"></span>
                                </div>
                            </div>
                            
                            <div class="col-md-12">       
                                <div class="col-md-2 table_title">
                                    <span ><span style="color:red;">*</span>品名 : </span>
                                </div>  
                                         
                                <div class="col-md-8">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="fullName" class="form-control" id="form_control_1" value="{{ old('fullName') }}">
                                        <label for="form_control_1"></label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span >單位 : </span>
                                </div>   
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="form_control_2" name="unit">
                                            <option value="0" {{ old('unit')== 0 ? 'selected' : '' }}> 請選擇 (需指定後才能進行採購進貨操作)</option>
                                            @foreach($material_units as $unit)
                                                <option value="{{ $unit->id }}" {{ old('unit')== $unit->id ? 'selected' : '' }}> {{$unit->name}}</option>
                                            @endforeach
                                        </select>
                                        <label for="form_control_2" style="color:#248ff1;"></label>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span ></span>
                                </div>   
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="cost" class="form-control" id="form_control_2" value="{{ old('cost') }}">
                                        <label for="form_control_2">預設每單位<span style="color:red">成本</span> (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="price" class="form-control" id="form_control_3" value="{{ old('price') }}">
                                        <label for="form_control_3">預設每單位<span style="color:red">售價</span> (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span ></span>
                                </div>   
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="size" class="form-control" id="form_control_4" value="{{ old('size') }}">
                                        <label for="form_control_4">尺寸</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="color" class="form-control" id="form_control_5" value="{{ old('color') }}">
                                        <label for="form_control_5">顏色</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span ></span>
                                </div>   
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="buy" class="form-control" id="form_control_6" value="{{ old('buy') }}">
                                        <label for="form_control_6">預設採購量 (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="safe" class="form-control" id="form_control_7" value="{{ old('safe') }}">
                                        <label for="form_control_7">安全量 (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                {{-- <div class="col-md-2 table_title">
                                    <span >倉儲位置 : </span>
                                </div>  
                                            
                                <div class="col-md-8">
                                    <div class="info-box hover-zoom-effect" >
                                        <div class="icon bg-cyan">
                                            <a href='javascript: openSelectWarehouse();'>
                                                <i class="glyphicon glyphicon-th-large" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        <div class="content">
                                            <div class="text">
                                                <a href="javascript:delete_warehouse();">
                                                    <i class="glyphicon glyphicon-remove" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            <div class="number col-blue-grey">
                                                <a href='javascript: openSelectWarehouse();'>
                                                    <div class="title col-orange" id="warehouse_show_1">
                                                            (需指定後才能進行庫存操作) 
                                                    </div>
                                                    <div><span id="warehouse_show_2"> 請點我選擇</span> 
                                                        @if(false)
                                                            <span style="color:red;"> (關閉)</span>
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-md-2 table_title">
                                    <span >倉儲位置 : </span>
                                </div>   
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="warehouse_id" name="warehouse_id">
                                            <option value="0" {{ old('warehouse_id')== 0 ? 'selected' : '' }}> 請選擇 </option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}" {{ old('warehouse_id')== $warehouse->id ? 'selected' : '' }}> {{$warehouse->code}}</option>
                                            @endforeach
                                        </select>
                                        <label for="warehouse_id" style="color:#248ff1;"></label>
                                    </div>
                                </div>
                            </div>
                            {{-- <input type="hidden" name="warehouse_id" id="warehouse_id"> --}}

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span ></span>
                                </div> 
                                <div class="col-md-8">
                                    <div class="form-group form-md-line-input">
                                        <textarea class="form-control" rows="3" name="memo" id="memo">{{ old('memo') }}</textarea>
                                        <label for="memo" style="color:#248ff1;font-size: 16px;">備註</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <div class="col-md-2 table_title">
                                        <span >啟用狀態:</span>
                                    </div> 
                                    <div class="col-md-8">
                                        <div class="md-radio-inline" style="margin-top:25px;">
                                            <div class="md-radio has-info">
                                                <input type="radio" id="radio1" name="status" class="md-radiobtn" value="1"
                                                @if(old('status') == 1 || old('status') == null)
                                                    checked
                                                @endif
                                                >
                                                <label for="radio1">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> 啟用 </label>
                                            </div>
                                            <div class="md-radio has-error">
                                                <input type="radio" id="radio2" name="status" class="md-radiobtn" value="2" {{ old('status') == 2 ? 'checked' : '' }}>
                                                <label for="radio2">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> 關閉 </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        {{-- file upload start --}}                                                
                        <div class="col-md-12">                                        
                            <div style="border: #248ff1 solid 2px;width:100%;height: 400px;">
                                <div class="col-md-12">
                                    <p style="font-size:18px;margin-top:18px;margin-left:20px;color:#248ff1;">檔案上傳</p>
                                    <hr>
                                </div>                        
                                <div class="col-md-4">
                                    <div class="col-md-6">                    
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="text" name="name_1" class="form-control" id="name_1" value="{{ old('name_1') }}">
                                            <label for="name_1">名稱</label>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">                    
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="{{ asset('assets/apps/img/no_image.png') }}" alt="" /> </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                            <div>
                                                <span class="btn blue btn-file">
                                                    <span class="fileinput-new" style=""> 選擇檔案 </span>
                                                    <span class="fileinput-exists"> 更改 </span>
                                                    <input type="file" name="upload_image_1"> </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> 移除 </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-6">                    
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="text" name="name_2" class="form-control" id="name_2" value="{{ old('name_2') }}">
                                            <label for="name_2">名稱</label>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">                    
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="{{ asset('assets/apps/img/no_image.png') }}" alt="" /> </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                            <div>
                                                <span class="btn blue btn-file">
                                                    <span class="fileinput-new" style=""> 選擇檔案 </span>
                                                    <span class="fileinput-exists"> 更改 </span>
                                                    <input type="file" name="upload_image_2"> </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> 移除 </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-6">                    
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="text" name="name_3" class="form-control" id="name_3" value="{{ old('name_3') }}">
                                            <label for="name_3">名稱</label>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">                    
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                <img src="{{ asset('assets/apps/img/no_image.png') }}" alt="" /> </div>
                                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"> </div>
                                            <div>
                                                <span class="btn blue btn-file">
                                                    <span class="fileinput-new" style=""> 選擇檔案 </span>
                                                    <span class="fileinput-exists"> 更改 </span>
                                                    <input type="file" name="upload_image_3"> </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> 移除 </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- file upload end --}}                        

                        <div class="col-md-12">    
                            <hr>                
                            <div class="form-actions noborder">
                                <button type="submit" class="btn"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('selectMaterial') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="fullCode" id="fullCode_input">
                </form>
                
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
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
<script src="{{asset('assets/apps/scripts/jquery.magnific-popup.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>





</body>
</html>

<script>

if($('#code_2').val() == '' || $('#code_2').val() == 'undefiend' || $('#material_category').val() == ''){
    $('#fullCode').html('資料尚未完整');
}

function showFullCode() {

    if($('#code_2').val() == '' || $('#code_2').val() == 'undefiend' || $('#material_category').val() == ''){
        $('#fullCode').html('資料尚未完整');   
    } else {
        var material_category = $('#material_category').val();
        var code_1 = $('#code_1').val();
        var code_2 = $('#code_2').val();
        var code_3 = $('#code_3').val();
        var dash_1 = '';

        if(code_3 == ''){
            dash_1 = '';
        } else {
            dash_1 = '-';
        }

        var str = material_category + code_1 + '-' + code_2 + dash_1 + code_3;
        $('#fullCode').html(str);
        $('#fullCode_input').val(str);

        
    }
}

function openSelectWarehouse() {
    $.magnificPopup.open({
        showCloseBtn : false, 
        enableEscapeKey : false,
        closeOnBgClick: true, 
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{{route('selectWarehouse')}}"}
    });
}

function setWarehouse(id,fullName,code,category){
    $.magnificPopup.close();
    var str = category+'                 <span class="col-blue-grey">'+fullName+'</span>';
    $('#warehouse_show_1').html(str);
    $('#warehouse_show_2').html(code);
    $('#warehouse_id').val(id);
}
function delete_warehouse(){
    $('#warehouse_show_1').html('(需指定後才能進行庫存操作)');
    $('#warehouse_show_2').html('請點我選擇');
    $('#warehouse_id').val('');    
}

</script>
