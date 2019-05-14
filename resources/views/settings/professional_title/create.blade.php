@extends('layouts.app')

@section('title','職稱建立')

@section('css')
<style>
    /* 初始label顏色 */
    .form-group.form-md-line-input.form-md-floating-label .form-control ~ label {
        color: #43a546; }
    /* help-block顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .help-block, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .help-block {
        color: #43a546;}
    /* focus後的label顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus {
        color: #43a546; }
    /* focus後的底線顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus:after, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus:after {
        background: #43a546; }
    button[type=submit]{
        color:#fff;
        background-color: #43a546;
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
    <h1 class="page-title"> 職稱建立
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
                <form role="form" action="{{ route('professional_title.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-body">

                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="name" class="form-control" id="form_control_1">
                            <label for="form_control_1">職稱</label>
                            <span class="help-block"></span>
                        </div>
                        
                    </div>
                    <div class="form-actions noborder">
                        <button type="submit" class="btn"><i class="fa fa-check"></i> 存 檔</button>
                        <a href="{{ route('professional_title.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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