@extends('layouts.app')

@section('title','新增盤點')

@section('css')
<link href="{{asset('assets/global/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />

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
    <h1 class="page-title"> 新增盤點
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
                <form role="form" action="{{ route('inventory.store') }}" method="POST" id="inquiry_from">
                    {{ csrf_field() }}
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ old('lot_number') }}">
                                    <label for="lot_number" style="color:#248ff1;">盤點名稱</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                盤點狀態 : <span style="color:#000">未開始</span>
                            </div>

                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                盤點單號 : <span style="color:#000">INV{{$inventory_no}}</span>
                                <input type="hidden" name="inventory_no" value="{{$inventory_no}}">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input">
                                    <select class="form-control" id="warehouse_category" name="warehouse_category">
                                        <option value="all" {{ old('warehouse_category')=='' ? 'selected' : '' }}>全部</option>
                                        @foreach($cates as $cate)
                                            <option value="{{$cate->id}}" {{ old('warehouse_category') == $cate->id ? 'selected' : '' }}> {{$cate->name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="warehouse_category" style="color:#248ff1;">倉庫分類</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="inventory_sdate" class="form-control" autocomplete="off" id="datepicker01" value="{{ old('inventory_sdate') }}">
                                    <label for="datepicker01" style="color:#248ff1;">盤點開始日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="inventory_edate" class="form-control" autocomplete="off" id="datepicker02" value="{{ old('inventory_edate') }}">
                                    <label for="datepicker02" style="color:#248ff1;">盤點結束日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo">{{ old('memo') }}</textarea>
                                    <label for="memo" style="color:#248ff1;font-size: 16px;">註解</label>
                                </div>
                            </div>

                        </div>



                        <div class="col-md-12">
                            <div class="form-actions noborder">
                                <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('inventory.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>

<button id="error_lot" class="btn btn-danger mt-sweetalert" data-title="盤點名稱 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_supplier" class="btn btn-danger mt-sweetalert" data-title="尚未選擇 供應商" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_askDate" class="btn btn-danger mt-sweetalert" data-title="詢價日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_expireDate" class="btn btn-danger mt-sweetalert" data-title="有效期限 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_material" class="btn btn-danger mt-sweetalert" data-title="未選擇任何物料" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
@endsection


@section('scripts')
<script src="{{asset('assets/global/plugins/jquery-ui/jquery-ui.js')}}" type="text/javascript"></script>
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="{{asset('assets/global/scripts/datatable.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{asset('assets/pages/scripts/table-datatables-buttons.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script src="{{asset('assets/apps/scripts/jquery.magnific-popup.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/pages/scripts/ui-sweetalert.min.js')}}" type="text/javascript"></script>

<script>

$( function() {
    $( "#datepicker01" ).datepicker();
    $( "#datepicker01" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker01" ).datepicker('option', 'firstDay', 1);

    $( "#datepicker02" ).datepicker();
    $( "#datepicker02" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker02" ).datepicker('option', 'firstDay', 1);

    $( "#datepicker03" ).datepicker();
    $( "#datepicker03" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker03" ).datepicker('option', 'firstDay', 1);
});

function submit_btn(){

    $("#inquiry_from").submit();
}
</script>
@endsection
