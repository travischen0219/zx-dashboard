@extends('layouts.app')

@section('title','物料')

@section('css')
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/apps/css/style.css')}}" rel="stylesheet" type="text/css" />

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

    #warehouse_show_all{
        cursor: not-allowed;        
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
    <h1 class="page-title"> 物料
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
                                                                 
                    <div class="form-body">
                        <div class="col-md-10">

                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span >分類 : </span>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="material_category" name="material_category" onchange="showFullCode();" disabled>
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
                                    <span>{{ $material->fullCode }} </span>
                                </div>
                            </div>
                            
                            <div class="col-md-12">       
                                <div class="col-md-2 table_title">
                                    <span >品名 : </span>
                                </div>  
                                         
                                <div class="col-md-8">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="fullName" class="form-control" id="form_control_1" value="{{ old('fullName')!='' ? old('fullName') : $material->fullName }}" readonly>
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
                                        <select class="form-control" id="form_control_2" name="unit" disabled>
                                            <option value="0" {{ $material->unit== 0 ? 'selected' : '' }}> 請選擇 (需指定後才能進行採購進貨操作)</option>
                                            @foreach($material_units as $unit)
                                                <option value="{{ $unit->id }}" {{ $material->unit == $unit->id ? 'selected' : '' }}> {{$unit->name}}</option>
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
                                        <input type="text" name="cost" class="form-control" id="form_control_2" value="{{ $material->cost }}" readonly>
                                        <label for="form_control_2">預設每單位<span style="color:red">成本</span> (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="price" class="form-control" id="form_control_3" value="{{ $material->price }}" readonly>
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
                                        <select class="form-control" id="cal_unit" name="cal_unit" disabled>
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
                                        <input type="text" name="cal_price" class="form-control" id="cal_price" value="{{ $material->cal_price }}" readonly>
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
                                        <input type="text" name="size" class="form-control" id="form_control_4" value="{{ old('size') != '' ? old('size') : $material->size }}" readonly>
                                        <label for="form_control_4">尺寸</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="color" class="form-control" id="form_control_5" value="{{ old('color') != '' ? old('color') : $material->color }}" readonly>
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
                                        <input type="text" name="buy" class="form-control" id="form_control_6" value="{{ old('buy') != '' ? old('buy') : $material->buy }}" readonly>
                                        <label for="form_control_6">預設採購量 (請輸入數字)</label>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                        <input type="text" name="safe" class="form-control" id="form_control_7" value="{{ old('safe') != '' ? old('safe') : $material->safe }}" readonly>
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
                                    <div class="info-box hover-zoom-effect" id="warehouse_show_all">
                                        <div class="icon bg-cyan">

                                                <i class="glyphicon glyphicon-th-large" aria-hidden="true"></i>

                                        </div>
                                        <div class="content">
                                            <div class="text">
                                                
                                            </div>
                                            <div class="number col-blue-grey">

                                                    <div class="title col-orange" id="warehouse_show_1">
                                                        @if($material->warehouse > 0)
                                                            {{$warehouse->warehouse_category->name}}       <span class="col-blue-grey"> {{$warehouse->fullName}}</span>
                                                        @else

                                                        @endif
                                                    </div>
                                                    <div><span id="warehouse_show_2">@if($material->warehouse > 0) {{$warehouse->code}} @else 尚未指定 @endif</span> 
                                                       
                                                    </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="col-md-2 table_title">
                                    <span ></span>
                                </div> 
                                <div class="col-md-8">
                                    <div class="form-group form-md-line-input">
                                        <textarea class="form-control" rows="3" name="memo" id="memo" readonly>{{ old('memo') != '' ? old('memo') : $material->memo }}</textarea>
                                        <label for="memo" style="color:#248ff1;font-size: 16px;">備註</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <div class="col-md-2 table_title">
                                        <span >啟用狀態 :</span>
                                    </div> 
                                    <div class="col-md-8">
                                        <div class="md-radio-inline" style="margin-top:25px">
                                            @if($material->status == 1)
                                                <span style="font-size: 16px;">啟用</span>
                                            @elseif($material->status == 2)
                                                <span style="font-size: 16px;">關閉</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- file upload start --}}                        
                        <div class="col-md-12">                                        
                            <div style="border: #248ff1 solid 2px;width:100%;height: 400px;">
                                <div class="col-md-12">
                                    <p style="font-size:18px;margin-top:18px;margin-left:20px;color:#248ff1;">檔案</p>
                                    <hr>
                                </div>  

                                @if($material->file_1 >0 || $material->file_2 >0 ||$material->file_3 >0)
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
                                                    <p style="margin-top:6px;">
                                                        @if($material->image_1->thumb_name != "file_image.jpg")
                                                            <a href="javascript:show_image('{{asset('upload/'.$material->image_1->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽</a> 
                                                        @endif
                                                        <a href="{{ url('settings/file_download',$material->image_1->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
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
                                                    <p style="margin-top:6px;">
                                                        @if($material->image_2->thumb_name != "file_image.jpg")
                                                            <a href="javascript:show_image('{{asset('upload/'.$material->image_2->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽</a> 
                                                        @endif
                                                        <a href="{{ url('settings/file_download',$material->image_2->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
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
                                                    <p style="margin-top:6px;">
                                                        @if($material->image_3->thumb_name != "file_image.jpg")
                                                            <a href="javascript:show_image('{{asset('upload/'.$material->image_3->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽</a> 
                                                        @endif
                                                        <a href="{{ url('settings/file_download',$material->image_3->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <p style="margin-left:20px;font-size: 18px;">無上傳檔案</p>
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
                                <a href="{{ route('materials.index') }}" class="btn blue"><i class="fa fa-reply"></i> 返 回</a>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="fullCode" id="fullCode_input">

            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>



@endsection


@section('scripts')
<script src="{{asset('assets/apps/scripts/jquery.magnific-popup.js')}}" type="text/javascript"></script>

<script>

function show_image(path) {
    $.magnificPopup.open({
        showCloseBtn : false, 
        enableEscapeKey : false,
        closeOnBgClick: true, 
        fixedContentPos: false,
        modal:false,
        type:'image',
        items:{src: path}
    });
}



</script>
@endsection
