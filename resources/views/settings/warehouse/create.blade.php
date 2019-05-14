@extends('layouts.app')

@section('title','新增倉儲')

@section('css')
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
    button[type=submit]{
        color:#fff;
        background-color: #248ff1;
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
    <h1 class="page-title"> 新增倉儲
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
    <div class="col-md-10 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light">
            @include('includes.messages')
            <div class="portlet-body form">
                <form role="form" action="{{ route('warehouses.store') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-body">


                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input">
                                    <select class="form-control" id="form_control_1" name="category">
                                        <option value="" {{ old('category')=='' ? 'selected' : '' }}>請選擇</option>
                                        @foreach($cates as $cate)
                                            <option value="{{$cate->id}}" {{ old('category') == $cate->id ? 'selected' : '' }}>{{$cate->name}} </option>
                                        @endforeach
                                    </select>
                                    <label for="form_control_1" style="color:#248ff1;"><span style="color:red;">*</span>分類</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" name="code" class="form-control" id="form_control_2" value="{{ old('code') }}">
                                    <label for="form_control_2"><span style="color:red;">*</span>倉儲編號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" name="fullName" class="form-control" id="form_control_3" value="{{ old('fullName') }}">
                                    <label for="form_control_3"><span style="color:red;">*</span>倉儲名稱</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" name="location" class="form-control" id="form_control_4" value="{{ old('location') }}">
                                    <label for="form_control_4">位置</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" name="size" class="form-control" id="form_control_5" value="{{ old('size') }}">
                                    <label for="form_control_5">空間尺寸</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-12">
                        
                            <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label" for="form_control_12" style="font-size:16px;color:#248ff1;">啟用狀態</label>
                                <div class="col-md-10">
                                    <div class="md-radio-inline">
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
                                <p style="font-size:18px;margin-top:18px;margin-left:20px;color:#248ff1;">檔案上傳<span style="color:red;">【每一檔案上傳限制5M】</span></p>
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
                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="form-actions noborder">
                                <button type="submit" class="btn"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('warehouses.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    
    
    </div>
</div>




@endsection


@section('scripts')
<script src="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>

@endsection