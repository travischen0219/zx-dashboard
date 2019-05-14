@extends('layouts.app')

@section('title','修改倉儲')

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
    <h1 class="page-title"> 修改倉儲
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
                <form role="form" action="{{ route('warehouses.update',$warehouse->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }} 
                    <div class="form-body">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input">
                                    <select class="form-control" id="form_control_1" name="category" disabled>
                                        @foreach($cates as $cate)
                                            <option value="{{$cate->id}}" {{ $warehouse->category == $cate->id ? 'selected' : '' }}>{{$cate->name}} </option>
                                        @endforeach
                                    </select>
                                    <label for="form_control_1" style="color:#248ff1;"><span style="color:red;">*</span>分類</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" name="code" class="form-control" id="form_control_2" value="{{ $warehouse->code }}" readonly>
                                    <label for="form_control_2"><span style="color:red;">*</span>倉儲編號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" name="fullName" class="form-control" id="form_control_3" value="{{ $warehouse->fullName }}" readonly>
                                    <label for="form_control_3"><span style="color:red;">*</span>倉儲名稱</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" name="location" class="form-control" id="form_control_4" value="{{ $warehouse->location }}" readonly>
                                    <label for="form_control_4">位置</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    <input type="text" name="size" class="form-control" id="form_control_5" value="{{ $warehouse->size }}" readonly>
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
                                        @if($warehouse->status == 1)
                                            <span style="font-size: 16px;">啟用</span>
                                        @elseif($warehouse->status == 2)
                                            <span style="font-size: 16px;">關閉</span>
                                        @endif
                                        
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
                                @if($warehouse->file_1 >0 || $warehouse->file_2 >0 ||$warehouse->file_3 >0)
                                    @if($warehouse->file_1 >0)
                                        <div class="col-md-4">
                                            <div class="thumbnail" style="width:180px;">
                                                @if($warehouse->image_1->thumb_name == "file_image.jpg")
                                                    <img src="{{asset('assets/apps/img/'.$warehouse->image_1->thumb_name)}}" alt="{{$warehouse->image_1->name}}">
                                                @else
                                                    <img src="{{asset('upload/'.$warehouse->image_1->thumb_name)}}" alt="{{$warehouse->image_1->name}}">
                                                @endif
                                                <div class="caption">
                                                    <h4 class="image_name">{{$warehouse->image_1->name}}</h4>
                                                    <p style="margin-top:6px;">
                                                        @if($warehouse->image_1->thumb_name != "file_image.jpg")
                                                            <a href="javascript:show_image('{{asset('upload/'.$warehouse->image_1->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽</a> 
                                                        @endif
                                                        <a href="{{ url('settings/file_download',$warehouse->image_1->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($warehouse->file_2 >0)
                                        <div class="col-md-4">
                                            <div class="thumbnail" style="width:180px;">
                                                @if($warehouse->image_2->thumb_name == "file_image.jpg")
                                                    <img src="{{asset('assets/apps/img/'.$warehouse->image_2->thumb_name)}}" alt="{{$warehouse->image_2->name}}">
                                                @else
                                                    <img src="{{asset('upload/'.$warehouse->image_2->thumb_name)}}" alt="{{$warehouse->image_2->name}}">
                                                @endif
                                                <div class="caption">
                                                    <h4 class="image_name">{{$warehouse->image_2->name}}</h4>
                                                    <p style="margin-top:6px;">
                                                        @if($warehouse->image_2->thumb_name != "file_image.jpg")
                                                            <a href="javascript:show_image('{{asset('upload/'.$warehouse->image_2->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽</a> 
                                                        @endif
                                                        <a href="{{ url('settings/file_download',$warehouse->image_2->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($warehouse->file_3 >0)
                                        <div class="col-md-4">
                                            <div class="thumbnail" style="width:180px;">
                                                @if($warehouse->image_3->thumb_name == "file_image.jpg")
                                                    <img src="{{asset('assets/apps/img/'.$warehouse->image_3->thumb_name)}}" alt="{{$warehouse->image_3->name}}">
                                                @else
                                                    <img src="{{asset('upload/'.$warehouse->image_3->thumb_name)}}" alt="{{$warehouse->image_3->name}}">
                                                @endif
                                                <div class="caption">
                                                    <h4 class="image_name">{{$warehouse->image_3->name}}</h4>
                                                    <p style="margin-top:6px;">
                                                        @if($warehouse->image_3->thumb_name != "file_image.jpg")
                                                            <a href="javascript:show_image('{{asset('upload/'.$warehouse->image_3->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽</a> 
                                                        @endif
                                                        <a href="{{ url('settings/file_download',$warehouse->image_3->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
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
                                <span>最後修改時間 : {{ $warehouse->updated_at }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="form-actions noborder">
                                <a href="{{ route('warehouses.index') }}" class="btn blue"><i class="fa fa-reply"></i> 返 回</a>
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