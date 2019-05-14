@extends('layouts.app')

@section('title','倉儲分類修改')

@section('css')
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
    <h1 class="page-title"> 倉儲分類修改
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
                <form role="form" action="{{ route('warehouse_category.update', $warehouse_category->id) }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}                                                  
                    <div class="form-body">

                        <div class="col-md-12">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="name" class="form-control" id="form_control_1" value="{{ $warehouse_category->name }}">
                                <label for="form_control_1">分類名稱</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        

                        <div class="form-group form-md-line-input">
                            <label class="col-md-2 control-label" for="form_control_12" style="font-size:16px;color:#248ff1;">啟用狀態</label>
                            <div class="col-md-10">
                                <div class="md-radio-inline">
                                    <div class="md-radio has-info">
                                        <input type="radio" id="radio1" name="status" class="md-radiobtn" value="1"
                                        @if( $warehouse_category->status == 1 )
                                            checked
                                        @endif
                                        >
                                        <label for="radio1">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> 啟用 </label>
                                    </div>
                                    <div class="md-radio has-error">
                                        <input type="radio" id="radio2" name="status" class="md-radiobtn" value="2" {{ $warehouse_category->status == 2 ? 'checked' : '' }}>
                                        <label for="radio2">
                                            <span></span>
                                            <span class="check"></span>
                                            <span class="box"></span> 關閉 </label>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12" style="margin-top:10px;">
                        <div class="well">
                            <span>最後修改人員 : {{ $updated_user->fullname }} @if($updated_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span><br>
                            <span>最後修改時間 : {{ $warehouse_category->updated_at }}</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-actions noborder">
                            <button type="submit" class="btn"><i class="fa fa-check"></i> 存 檔</button>
                            <a href="{{ route('warehouse_category.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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

@endsection