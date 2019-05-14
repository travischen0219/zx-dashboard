@extends('layouts.app')

@section('title','半成品進度紀錄')

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

    html input[disabled]{
        cursor: not-allowed;
    }
    html input[readonly]{
        cursor: not-allowed;
    }
    html select[disabled]{
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
    <h1 class="page-title"> 半成品進度紀錄
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
                <form role="form" action="{{ route('processing_list.update',$processing->id) }}" method="POST" id="sale_from" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}                                                
                    
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $processing->lot_number }}" disabled>
                                    <label for="lot_number" style="color:#248ff1;">名稱</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            
                            {{-- @if($processing->status == 1) --}}
                                <div class="col-md-3">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="status" name="status" disabled>
                                            <option value="1" {{$processing->status == 1 ? 'selected' : ''}}>加工中</option>
                                            {{-- <option value="2" {{$processing->status == 2 ? 'selected' : ''}}>加工完成</option> --}}
                                        </select>
                                        <label for="status" style="color:#248ff1;">狀態</label>
                                    </div>
                                </div>
                            {{-- @elseif($processing->status == 2)
                                <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                    狀態 : <span style="color:blue">加工完成</span> 
                                </div>
                            @endif --}}
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="createDate" class="form-control" id="datepicker01" disabled>
                                    <label for="datepicker01" style="color:#248ff1;">開始日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="expireDate" class="form-control" id="datepicker02" {{ $processing->status == 2 ? 'disabled' : ''}}>
                                    <label for="datepicker02" style="color:#248ff1;">完成日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div> --}}
                        </div>

                        <div class="col-md-12">
                            {{-- <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo" disabled>{{ $processing->memo }}</textarea>
                                    <label for="memo" style="color:#248ff1;font-size: 16px;">備註</label>
                                </div>
                            </div> --}}
                        </div>

                        <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered" style="background-color: rgb(240, 240, 240);">
                                <div class="portlet-title">
                                    <div class="caption font-dark" style="">
                                        <span class="caption-subject"> 物料清單</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <div style="margin-left:7px;margin-bottom: 10px;">
                                        {{-- <a href="javascript:addMaterial();" class="btn btn-primary"><i class="fa fa-plus"></i> 新增物料</a> --}}
                                            
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>

                                                    <th width="30%"> 物料 </th>
                                                    <th width="20%"> 單位 </th>     
                                                    <th width="30%"> 倉儲位置 </th>                                                                                                                                                                                            
                                                    <th width="20%"> 加工數量 </th>

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




                        <div class="col-md-12">
                            <!-- BEGIN EXAMPLE TABLE PORTLET-->
                            <div class="portlet light bordered" style="background-color: #eafbff;">
                                <div class="portlet-title">
                                    <div class="caption font-dark" >
                                        <span class="caption-subject"> 加工紀錄</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <div style="margin-left:7px;margin-bottom: 10px;">
                                        <a href="javascript:addMaterial();" class="btn btn-primary"><i class="fa fa-plus"></i> 新增加工</a>
                                            
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table id="materialTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="5%"> 操作 </th>     
                                                    <th width="15%"> 廠商 </th>     
                                                    <th width="10%"> 加工方式 </th>
                                                    <th width="15%"> 開始日期 </th>
                                                    <th width="15%"> 完成日期 </th>
                                                    <th width="10%"> 狀態 </th>                                                                                                                                                                                            
                                                    <th width="30%"> 備註 </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                {!! $data_process !!}
                                                
                                                
                                            </tbody>
                                        </table>


                                    </div>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>


                        {{-- <div class="col-md-12" style="margin-top:10px;">
                            <div class="well">
                                <span>最後修改人員 : {{ $updated_user->fullname }} @if($updated_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span><br>
                                <span>最後修改時間 : {{ $processing->updated_at }}</span>
                            </div>
                        </div> --}}

                    
                        <div class="col-md-12">        
                            <div class="form-actions noborder">
                                <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('semi_finished_schedule.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                            </div>
                        </div>
                    </div>
                    
                </form>
                <input type="hidden" id="materialCount" value="{{ $materialCount }}">
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>


{{-- <button id="error_lot" class="btn btn-danger mt-sweetalert" data-title="名稱 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_customer" class="btn btn-danger mt-sweetalert" data-title="尚未選擇 客戶" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_applyDate" class="btn btn-danger mt-sweetalert" data-title="新增日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
{{-- <button id="error_expireDate" class="btn btn-danger mt-sweetalert" data-title="有效期限 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button> --}}
<button id="error_check_has_manufacturer" class="btn btn-danger mt-sweetalert" data-title="未選擇任何加工" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_check_manufacturer" class="btn btn-danger mt-sweetalert" data-title="有廠商未選擇" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_check_function" class="btn btn-danger mt-sweetalert" data-title="有加工方式未選擇" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_check_date" class="btn btn-danger mt-sweetalert" data-title="完成或取消加工的開始與結束日期必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_end_date" class="btn btn-danger mt-sweetalert" data-title="完成日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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
function selectSupplier() {
    $.magnificPopup.open({
        showCloseBtn : false, 
        enableEscapeKey : false,
        closeOnBgClick: true, 
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{{route('selectSupplier')}}"}
    });
}

function setSupplier(code,name,id){
    $.magnificPopup.close();
    var str = code+' '+name;
    $('#select_supplier').text(str);
    $('#supplier').val(id);
}


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

var materialCount = $("#materialCount").val();
var currentMaterial = 0;
function addMaterial() {
    $.post(
        "{{ route('processing.addRow') }}", 
        {'_token':"{{csrf_token()}}",'materialCount': materialCount},
        function(response) {
            $("#materialTable").append(response);

            materialCount++;
        }
    );
}

function delMaterial(id) {
    $("#materialRow" + id).fadeOut('fast', function() {
        $(this).remove();
        total();
    });
}

function openSelectManufacturer(id) {
    currentMaterial = id;
    $.magnificPopup.open({
        showCloseBtn : false, 
        enableEscapeKey : false,
        closeOnBgClick: true, 
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{{route('selectManufacturer')}}"}
    });
}

function setManufacturer(code,name,id){
    $.magnificPopup.close();
    var str = code+' '+name;
    $('#manufacturerName' + currentMaterial).text(str);
    $('#manufacturer' + currentMaterial).val(id);
    
}

function submit_btn(){
   
    var check_has_manufacturer = 0;
    var check_manufacturer = 0;
    var check_function = 0;
    var check_date = 0;
    $(".materialRow").each(function(index, el) {
        if($(this).find(".select_manufacturer").val() != ''){
            check_has_manufacturer++;
        }
        if($(this).find(".select_manufacturer").val() == ''){
            check_manufacturer++;
        }
        if($(this).find(".select_processFunction").val() == 0){
            check_function++;
        }

        if($(this).find(".select_pprocessStatus").val() == 2 || $(this).find(".select_pprocessStatus").val() == 3){
            if($(this).find(".select_startDate").val() == '' || $(this).find(".select_endDate").val() == ''){
                check_date++;
            }
        }        
    });
    if(check_has_manufacturer == 0){
        $('#error_check_has_manufacturer').click();
        return;
    }
    if(check_manufacturer > 0){
        $('#error_check_manufacturer').click();
        return;
    }
    if(check_function > 0){
        $('#error_check_function').click();
        return;
    }
    if(check_date > 0){
        $('#error_check_date').click();
        return;
    }
    
    $("#sale_from").submit();

}
</script>
@endsection