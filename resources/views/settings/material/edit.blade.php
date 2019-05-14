@extends('layouts.app')

@section('title','物料修改')

@section('css')
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/apps/css/style.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css')}}" rel="stylesheet" type="text/css" />

<style>
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


@endsection

@section('page_header')
<!-- BEGIN PAGE HEADER-->

<!-- BEGIN PAGE BAR -->
<div class="page-bar">

    <!-- BEGIN THEME PANEL -->
    @include('layouts.theme_panel')    
    <!-- END THEME PANEL -->


    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 物料修改
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light">
            @include('includes.messages')

            <div class="portlet-body form">
                <form role="form" action="{{ route('materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}                                                    
                    <div class="form-body">
                        <div class="col-md-10">

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span >分類 : </span>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="material_category" name="material_category" onchange="showFullCode();">
                                            <option value="" {{ old('material_category') == '' ? 'selected' : '' }}>請選擇</option>
                                            @foreach($material_categories as $cate)
                                                @if(old('material_category'))
                                                    <option value="{{$cate->code}}" {{ old('material_category') == $cate->code ? 'selected' : '' }}>[ {{$cate->code}} ] {{$cate->name}} </option>
                                                @else
                                                    <option value="{{$cate->code}}" {{ $material->material_categories_code == $cate->code ? 'selected' : '' }}>[ {{$cate->code}} ] {{$cate->name}} </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="material_category" style="color:#248ff1;"></label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">                        
                                <div class="col-md-2 table_title">
                                    <span >完整編號 : </span>
                                </div>
                                <div class="col-md-5 table_content">
                                    <span>{{ $material->fullCode }} </span><span style="color:red"> (不可更改)</span>
                                </div>
                            </div>
                            
                            <div class="col-md-12">       
                                <div class="col-md-2 table_title">
                                    <span >品名 : </span>
                                </div>  
                                         
                                <div class="col-md-8">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="fullName" class="form-control" id="form_control_1" value="{{ old('fullName')!='' ? old('fullName') : $material->fullName }}">
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
                                        <select class="form-control" id="unit" name="unit">
                                            <option value="0" {{ $material->unit== 0 ? 'selected' : '' }}> 請選擇 (需指定後才能進行採購進貨操作)</option>
                                            @foreach($material_units as $unit)
                                                <option value="{{ $unit->id }}" {{ $material->unit == $unit->id ? 'selected' : '' }}> {{$unit->name}}</option>
                                            @endforeach
                                        </select>
                                        <label for="unit" style="color:#248ff1;"></label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span ></span>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="cost" class="form-control" id="form_control_2" value="{{ old('cost') != '' ? old('cost') : $material->cost }}">
                                        <label for="form_control_2">預設每單位<span style="color:red">成本</span> (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="price" class="form-control" id="form_control_3" value="{{ old('price') != '' ? old('price') : $material->price }}">
                                        <label for="form_control_3">預設每單位<span style="color:red">售價</span> (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span >計價單位 : </span>
                                </div>   
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="cal_unit" name="cal_unit">
                                            <option value="0" {{ $material->cal_unit == 0 ? 'selected' : '' }}> 未指定</option>
                                            @foreach($material_units as $unit)
                                                <option value="{{ $unit->id }}" {{ $material->cal_unit == $unit->id ? 'selected' : '' }}> {{$unit->name}}</option>
                                            @endforeach
                                        </select>
                                        <label for="cal_unit" style="color:#248ff1;"></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="cal_price" class="form-control" id="cal_price" value="{{ $material->cal_price }}">
                                        <label for="cal_price">計價價格 (請輸入數字)</label>
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
                                        <input type="text" name="size" class="form-control" id="form_control_4" value="{{ old('size') != '' ? old('size') : $material->size }}">
                                        <label for="form_control_4">尺寸</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="color" class="form-control" id="form_control_5" value="{{ old('color') != '' ? old('color') : $material->color }}">
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
                                        <input type="text" name="buy" class="form-control" id="form_control_6" value="{{ old('buy') != '' ? old('buy') : $material->buy }}">
                                        <label for="form_control_6">預設採購量 (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="safe" class="form-control" id="form_control_7" value="{{ old('safe') != '' ? old('safe') : $material->safe }}">
                                        <label for="form_control_7">安全量 (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
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
                                                        @if($material->warehouse > 0)
                                                            {{$warehouse->warehouse_category->name}}       <span class="col-blue-grey"> {{$warehouse->fullName}}</span>
                                                        @else
                                                            (需指定後才能進行庫存操作)
                                                        @endif
                                                    </div>
                                                    <div><span id="warehouse_show_2">@if($material->warehouse > 0) {{$warehouse->code}} @else 請點我選擇 @endif</span> 
                                                        @if(false)
                                                            <span style="color:red;"> (關閉)</span>
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="warehouse_id" id="warehouse_id" value="{{ $material->warehouse }}">

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span ></span>
                                </div> 
                                <div class="col-md-8">
                                    <div class="form-group form-md-line-input">
                                        <textarea class="form-control" rows="3" name="memo" id="memo">{{ old('memo') != '' ? old('memo') : $material->memo }}</textarea>
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
                                                @if($material->status == 1 || $material->status == null)
                                                    checked
                                                @endif
                                                >
                                                <label for="radio1">
                                                    <span></span>
                                                    <span class="check"></span>
                                                    <span class="box"></span> 啟用 </label>
                                            </div>
                                            <div class="md-radio has-error">
                                                <input type="radio" id="radio2" name="status" class="md-radiobtn" value="2" {{ $material->status == 2 ? 'checked' : '' }}>
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
                                    <p style="font-size:18px;margin-top:18px;margin-left:20px;color:#248ff1;">檔案上傳<span style="color:red;">【每一檔案上傳限制5M；操作新增時勿同時做刪除舊檔案動作，可先刪除再新增，或先新增存檔再刪除，新增和刪除檔案請分開兩次處理】</span></p>
                                    <hr>
                                </div>  

                                @if($material->file_1 >0)

                                    <div class="col-md-4">
                                        <div class="thumbnail" style="width:180px;">
                                            @if($material->image_1->thumb_name == "file_image.jpg")
                                                <img src="{{asset('assets/apps/img/'.$material->image_1->thumb_name)}}" alt="{{$material->image_1->name}}">
                                            @else
                                                <img src="{{asset('upload/'.$material->image_1->thumb_name)}}" alt="{{$material->image_1->name}}">
                                            @endif
                                            <div class="caption">
                                                <h4 class="image_name">{{$material->image_1->name}}</h4>
                                                <p style="margin-top:6px;margin-bottom: 50px">
                                                    <a href="javascript:;" class="btn red pull-right btn-sm" role="button" onclick="
                                                        if(confirm('確定要刪除嗎 ?')){
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{$material->image_1->id}}').submit();
                                                        } else {
                                                            event.preventDefault();
                                                        }">刪除</a>
                                                    
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                @endif

                                @if($material->file_2 >0)

                                    <div class="col-md-4">
                                        <div class="thumbnail" style="width:180px;">
                                            @if($material->image_2->thumb_name == "file_image.jpg")
                                                <img src="{{asset('assets/apps/img/'.$material->image_2->thumb_name)}}" alt="{{$material->image_2->name}}">
                                            @else
                                                <img src="{{asset('upload/'.$material->image_2->thumb_name)}}" alt="{{$material->image_2->name}}">
                                            @endif
                                            <div class="caption">
                                                <h4 class="image_name">{{$material->image_2->name}}</h4>
                                                <p style="margin-top:6px;margin-bottom: 50px">
                                                    <a href="javascript:;" class="btn red pull-right btn-sm" role="button" onclick="
                                                        if(confirm('確定要刪除嗎 ?')){
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{$material->image_2->id}}').submit();
                                                        } else {
                                                            event.preventDefault();
                                                        }">刪除</a>
                                                    
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                @endif

                                @if($material->file_3 >0)

                                    <div class="col-md-4">
                                        <div class="thumbnail" style="width:180px;">
                                            @if($material->image_3->thumb_name == "file_image.jpg")
                                                <img src="{{asset('assets/apps/img/'.$material->image_3->thumb_name)}}" alt="{{$material->image_3->name}}">
                                            @else
                                                <img src="{{asset('upload/'.$material->image_3->thumb_name)}}" alt="{{$material->image_3->name}}">
                                            @endif
                                            <div class="caption">
                                                <h4 class="image_name">{{$material->image_3->name}}</h4>
                                                <p style="margin-top:6px;margin-bottom: 50px">
                                                    <a href="javascript:;" class="btn red pull-right btn-sm" role="button" onclick="
                                                        if(confirm('確定要刪除嗎 ?')){
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{$material->image_3->id}}').submit();
                                                        } else {
                                                            event.preventDefault();
                                                        }">刪除</a>
                                                    
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                @endif

                                @if($upload_check_1)
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
                                @endif
                                @if($upload_check_2)
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
                                @endif
                                @if($upload_check_3)
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
                                @endif 
                            </div>
                        </div>
                        {{-- file upload end --}}

                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="well">
                                <span>最後修改人員 : {{ $updated_user->fullname }} @if($updated_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span><br>
                                <span>最後修改時間 : {{ $material->updated_at }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">         
                            <div class="form-actions noborder">
                                <button type="submit" class="btn"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('materials.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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
@if($material->file_1 > 0)
    <form id="delete-form-{{$material->image_1->id}}" action="{{ url('settings/material_file/delete/1/'. $material->id.'/'.$material->image_1->id) }}" method="post" style="display:none">
        {{ csrf_field() }}
    </form>
@endif
@if($material->file_2 > 0)
    <form id="delete-form-{{$material->image_2->id}}" action="{{ url('settings/material_file/delete/2/'. $material->id.'/'.$material->image_2->id) }}" method="post" style="display:none">
        {{ csrf_field() }}
    </form>
@endif
@if($material->file_3 > 0)
    <form id="delete-form-{{$material->image_3->id}}" action="{{ url('settings/material_file/delete/3/'. $material->id.'/'.$material->image_3->id) }}" method="post" style="display:none">
        {{ csrf_field() }}
    </form>
@endif


@endsection


@section('scripts')
<script src="{{asset('assets/apps/scripts/jquery.magnific-popup.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>

<script>

// if($('#code_2').val() == '' || $('#code_2').val() == 'undefiend' || $('#material_category').val() == ''){
//     $('#fullCode').html('資料尚未完整');
// }

// function showFullCode() {

//     if($('#code_2').val() == '' || $('#code_2').val() == 'undefiend' || $('#material_category').val() == ''){
//         $('#fullCode').html('資料尚未完整');
//         $('#fullCode_input').val(null);
//     } else {
//         var material_category = $('#material_category').val();
//         var code_1 = $('#code_1').val();
//         var code_2 = $('#code_2').val();
//         var code_3 = $('#code_3').val();
//         var dash_1 = '';

//         if(code_3 == ''){
//             dash_1 = '';
//         } else {
//             dash_1 = '-';
//         }

//         var str = material_category + code_1 + '-' + code_2 + dash_1 + code_3;
//         $('#fullCode').html(str);
//         $('#fullCode_input').val(str);

        
//     }
// }

// showFullCode();

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
@endsection