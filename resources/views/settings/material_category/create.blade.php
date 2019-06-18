@extends('layouts.app')

@section('title','物料分類建立')

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
    <h1 class="page-title"> 物料分類建立
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
                <form role="form" action="{{ route('material_category.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-body">

                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="code" class="form-control" id="form_control_1" value="{{ old('code') }}">
                            <label for="form_control_1"><span style="color:red;">*</span>分類代號</label>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="name" class="form-control" id="form_control_2" value="{{ old('name') }}">
                            <label for="form_control_2"><span style="color:red;">*</span>分類名稱</label>
                            <span class="help-block"></span>
                        </div>
                        <div class="form-group">
                            <label class="text-primary">計價欄位：</label>
                            <label>
                                <input type="radio" name="cal" id="form_control_3" value="1" {{ old('cal') == 1 ? 'checked' : '' }}> 有
                            </label>
                            <label style="margin-left: 10px;">
                                <input type="radio" name="cal" id="form_control_4" value="0" {{ old('cal') != 1 ? 'checked' : '' }}> 無
                            </label>
                        </div>
                    </div>
                    <div class="form-actions noborder">
                        <button type="submit" class="btn"><i class="fa fa-check"></i> 存 檔</button>
                        <a href="{{ route('material_category.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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
