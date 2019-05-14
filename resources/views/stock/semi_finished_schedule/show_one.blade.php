@extends('layouts.app')

@section('title','半成品進度追蹤')

@section('css')
<link href="{{asset('assets/global/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />
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

    html input[readonly]{
        cursor: not-allowed;
    }
    html input[disabled]{
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
    <h1 class="page-title"> 半成品進度追蹤
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

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $processing->lot_number }}" disabled>
                                    <label for="lot_number" style="color:#248ff1;">名稱</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            
                            
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                狀態 : <span style="color:blue">加工完成</span> 
                            </div>

                           
                            
                        </div>

                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="start_date" class="form-control" id="datepicker01" disabled>
                                    <label for="datepicker01" style="color:#248ff1;">開始日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="end_date" class="form-control" id="datepicker02" disabled>
                                    <label for="datepicker02" style="color:#248ff1;">完成日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                           

                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo" disabled>{{ $processing->memo }}</textarea>
                                    <label for="memo" style="color:#248ff1;font-size: 16px;">備註</label>
                                </div>
                            </div>
                            
                        </div>


                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption font-dark" style="">
                                        <span class="caption-subject"> 物料清單</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <div style="margin-left:7px;margin-bottom: 10px;">                                            
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table id="materialTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="10%"> 操作 </th>
                                                    <th width="30%"> 物料 </th>
                                                    <th width="10%"> 單位 </th>     
                                                    <th width="20%"> 倉儲位置 </th>                                                                                                                                               
                                                    <th width="15%"> 目前庫存 </th>                                                 
                                                    <th width="15%"> 加工數量 </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                {!! $data !!}
                                                
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>


                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="well">
                                <span>最後修改人員 : {{ $updated_user->fullname }} @if($updated_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span><br>
                                <span>最後修改時間 : {{ $processing->updated_at }}</span>
                            </div>
                        </div>

                    
                        <div class="col-md-12">        
                            <div class="form-actions noborder">
                                <a href="{{ route('semi_finished_schedule.index') }}" class="btn blue"><i class="fa fa-reply"></i> 返 回</a>   
                            </div>
                        </div>
                    </div>
                    


            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>

{{-- @if($processing->file_1 > 0)
    <form id="delete-form-{{$processing->image_1->id}}" action="{{ url('shopping/processing_file/delete/1/'. $processing->id.'/'.$processing->image_1->id) }}" method="post" style="display:none">
        {{ csrf_field() }}
    </form>
@endif
@if($processing->file_2 > 0)
    <form id="delete-form-{{$processing->image_2->id}}" action="{{ url('shopping/processing_file/delete/2/'. $processing->id.'/'.$processing->image_2->id) }}" method="post" style="display:none">
        {{ csrf_field() }}
    </form>
@endif
@if($processing->file_3 > 0)
    <form id="delete-form-{{$processing->image_3->id}}" action="{{ url('shopping/processing_file/delete/3/'. $processing->id.'/'.$processing->image_3->id) }}" method="post" style="display:none">
        {{ csrf_field() }}
    </form>
@endif --}}

{{-- <button id="error_lot" class="btn btn-danger mt-sweetalert" data-title="名稱 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_customer" class="btn btn-danger mt-sweetalert" data-title="尚未選擇 客戶" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_applyDate" class="btn btn-danger mt-sweetalert" data-title="新增日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_expireDate" class="btn btn-danger mt-sweetalert" data-title="有效期限 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
<button id="error_end_date" class="btn btn-danger mt-sweetalert" data-title="完成日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_material" class="btn btn-danger mt-sweetalert" data-title="未選擇任何物料" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
{{-- <button id="error_amount" class="btn btn-danger mt-sweetalert" data-title="加工數量不可為零或負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_price" class="btn btn-danger mt-sweetalert" data-title="單價不可為負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_sale_no_prefix" class="btn btn-danger mt-sweetalert" data-title="銷貨單號開頭為 S" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_sale_no" class="btn btn-danger mt-sweetalert" data-title="銷貨單號長度有誤" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_stock_date" class="btn btn-danger mt-sweetalert" data-title="處理日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_material" class="btn btn-danger mt-sweetalert" data-title="未選擇任何物料" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
<button id="error_warehouse" class="btn btn-danger mt-sweetalert" data-title="倉儲位置必選" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_quantity" class="btn btn-danger mt-sweetalert" data-title="加工數量不可為零或負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_calculate" class="btn btn-danger mt-sweetalert" data-title="有庫存操作後為負數，請確認數量正確性" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_quantity_number" class="btn btn-danger mt-sweetalert" data-title="數量請輸入數字" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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
<script src="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>

<script>

$( function() {
    $( "#datepicker01" ).datepicker();
    $( "#datepicker01" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker01" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker01" ).datepicker('setDate', "{{ $processing->start_date }}");
    
    $( "#datepicker02" ).datepicker();
    $( "#datepicker02" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker02" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker02" ).datepicker('setDate', "{{ $processing->end_date }}");
  
});
</script>
@endsection