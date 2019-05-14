@extends('layouts.app')

@section('title','圖片上傳')

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

    

    .form-group.form-md-line-input .form-control::-moz-placeholder {
        color: #248ff1;}
    .form-group.form-md-line-input .form-control:-ms-input-placeholder {
        color: #248ff1; }
    .form-group.form-md-line-input .form-control::-webkit-input-placeholder {
        color: #248ff1; }

    .form-horizontal .form-group.form-md-line-input > label {
        color: #248ff1; }

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
    <h1 class="page-title"> 圖片上傳
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
    <div class="col-md-6 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light">
            @include('includes.messages')
            <div class="portlet-body form">
                <form role="form" action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-body">

                        <div class="col-md-12">                    
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}">
                                <label for="name">名稱</label>
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
                                        <span class="fileinput-new" style=""> 選擇圖片 </span>
                                        <span class="fileinput-exists"> 更改 </span>
                                        <input type="file" name="upload_image"> </span>
                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> 移除 </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">    
                            <div class="form-actions noborder">
                                <button type="submit" class="btn"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('gallery.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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
<script>
    // var str = "測試";
    // var arr = str.match(/[^\x00-\xff]/ig); 
	// if(arr) {
    //     alert(str.length + arr.length); 
    // } else {
    //     alert(str.length);
    // }


</script>

@endsection