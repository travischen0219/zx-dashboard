@extends('layouts.app')

@section('title','編輯採購轉入庫')

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

    button[type=submit]{
        color:#fff;
        background-color: #248ff1;
    }

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
    <h1 class="page-title"> 編輯採購轉入庫
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
                <form role="form" action="{{ route('ibuy_to_stock.update',$buy_to_stock->id) }}" method="POST" id="buy_to_stock_from">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >

                                    <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $buy_to_stock->lot_number }}" disabled>

                                    <label for="lot_number" style="color:#248ff1;">批號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                供應商 :
                                <button type="button" class="btn blue">{{ $buy_to_stock->supplier_name->code.' '.$buy_to_stock->supplier_name->shortName}}</button>
                            </div>

                            @if($buy_to_stock->status == 1)
                                <div class="col-md-3">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="status" name="status">
                                            <option value="1" {{$buy_to_stock->status == 1 ? 'selected' : ''}}>待入庫</option>
                                            <option value="2" {{$buy_to_stock->status == 2 ? 'selected' : ''}}>已轉入庫</option>
                                        </select>
                                        <label for="status" style="color:#248ff1;">轉入庫狀態</label>
                                    </div>
                                </div>
                            @elseif($buy_to_stock->status == 2)
                                <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                    轉入庫狀態 : <span style="color:blue">已轉入庫</span>
                                </div>
                            @endif

                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                採購單號 : <span style="color:#000">P{{$buy_to_stock->buy_no}}</span>
                            </div>

                        </div>
                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="realReceiveDate" class="form-control" autocomplete="off" id="datepicker02" disabled>
                                    <label for="datepicker02" style="color:#248ff1;">實際到貨日 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="inStockDate" class="form-control" autocomplete="off" id="datepicker03" {{ $buy_to_stock->status == 2 ? 'disabled' : ''}}>
                                    <label for="datepicker03" style="color:#248ff1;">入庫日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo" {{ $buy_to_stock->status == 2 ? 'disabled' : ''}}>{{ $buy_to_stock->memo }}</textarea>
                                    <label for="memo" style="color:#248ff1;font-size: 16px;">入庫註解</label>
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
                                        @if($buy_to_stock->status == 1)
                                            <a href="javascript:addMaterial();" class="btn btn-primary"><i class="fa fa-plus"></i> 新增物料</a>

                                        @else

                                        @endif
                                    </div>

                                    <div class="table-responsive">
                                        <table id="materialTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="10%"> 操作 </th>
                                                    <th width="30%"> 物料 </th>
                                                    <th width="15%"> 單位 </th>
                                                    <th width="30%"> 倉儲 </th>
                                                    <th width="15%"> 數量 </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {!! $data !!}

                                            </tbody>
                                        </table>
                                        <hr>

                                    </div>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>

                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="well">
                                <span>最後修改人員 : {{ $updated_user->fullname }} @if($updated_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span><br>
                                <span>最後修改時間 : {{ $buy_to_stock->updated_at }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-actions noborder">
                                @if($buy_to_stock->status == 1)
                                    <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                    <a href="{{ route('ibuy_to_stock.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                                @elseif($buy_to_stock->status == 2)
                                    <a href="{{ route('ibuy_to_stock.index') }}" class="btn" style="color:#fff;background-color: #248ff1;"><i class="fa fa-reply"></i> 返 回</a>
                                @endif
                            </div>

                        </div>
                    </div>
                </form>
                <input type="hidden" id="materialCount" value="{{ $materialCount }}">
                <input type="hidden" id="buy_id" value="{{ $buy_to_stock->id }}">
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>

<button id="error_inStockDate" class="btn btn-danger mt-sweetalert" data-title="入庫日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_material" class="btn btn-danger mt-sweetalert" data-title="未選擇任何物料" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_warehouse" class="btn btn-danger mt-sweetalert" data-title="倉儲位置必選" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_quantity" class="btn btn-danger mt-sweetalert" data-title="有數量未填寫" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_amount" class="btn btn-danger mt-sweetalert" data-title="入庫數量為零或負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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
<script>



$( function() {

    $( "#datepicker02" ).datepicker();
    $( "#datepicker02" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker02" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker02" ).datepicker('setDate', "{{ $buy_to_stock->realReceiveDate }}");

    $( "#datepicker03" ).datepicker();
    $( "#datepicker03" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker03" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker03" ).datepicker('setDate', "{{ $buy_to_stock->inStockDate }}");

});

var materialCount = $("#materialCount").val();
var currentMaterial = 0;

function addMaterial() {
    $.post(
        "{{ route('select_material_toStock.addRow') }}",
        {'_token':"{{csrf_token()}}",'materialCount': materialCount},
        function(response) {
            $("#materialTable").append(response);
            total();
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

function openSelectMaterial(id) {
    currentMaterial = id;
    var buy_id = $('#buy_id').val();
    var url_str = 'settings/selectMaterial_byId/'+buy_id;

    $.magnificPopup.open({
        showCloseBtn : false,
        enableEscapeKey : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{!!url('"+url_str+"')!!}"}
    });
}

function setMaterial(code,name,buy,unit,cost,price,id,unit_name,warehouse_name,warehouse_id){
    $.magnificPopup.close();
    var str = code+' '+name;
    $('#materialName' + currentMaterial).text(str);
    $('#material' + currentMaterial).val(id);
    $('#materialUnit' + currentMaterial).html(unit_name);
    if(warehouse_id > 0){
        $('#materialWarehouseName' + currentMaterial).html(warehouse_name);
        $('#materialWarehouse' + currentMaterial).val(warehouse_id);
    } else {
        $('#materialWarehouseName' + currentMaterial).html('請選擇倉儲');
        $('#materialWarehouse' + currentMaterial).val('');
    }
    total();
}

function openSelectWarehouse(id) {
    currentMaterial = id;
    if($('#material' + currentMaterial).val()){
        var material_id = $('#material' + currentMaterial).val();
        var url_str = 'settings/selectWarehouse_stock/'+material_id;
        $.magnificPopup.open({
            showCloseBtn : false,
            enableEscapeKey : false,
            closeOnBgClick: true,
            fixedContentPos: false,
            modal:false,
            type:'iframe',
            items:{src:"{!!url('"+url_str+"')!!}"}
        });
    } else {
        return;
    }
}

function setWarehouse(id,fullName,code,category){
    $.magnificPopup.close();
    $('#materialWarehouseName' + currentMaterial).html(code);
    $('#materialWarehouse' + currentMaterial).val(id);
}

function total() {
    var total = 0;

    $(".materialRow").each(function(index, el) {
        var subTotal = 0;
        var subAmount = $(this).find(".materialAmount").val();
        var subPrice = $(this).find(".materialPrice").val();
        if(isNaN(subAmount) || isNaN(subPrice)) {
            $(this).find(".materialSubTotal").html("請輸入數字");
        } else {
            total += subAmount * subPrice;
            $(this).find(".materialSubTotal").html(subAmount * subPrice);
        }
    });

    $("#materialTotal").html(total);
}

$(function() {
    total();
});

function submit_btn(){

    if($('#status').val()==2){
        if($('#datepicker03').val() == ''){
            $('#error_inStockDate').click();
            return;
        }
    }

    var check_material = 0;
    var check_warehouse = 0;
    var check_same_material_and_warehouse = 0;
    var material_array = [] ;
    var same_material_and_warehouse = '';
    var check_quantity = 0;
    var check_calculate = 0;
    var check_quantity_number = 0;
    $(".materialRow").each(function(index, el) {
        if($(this).find(".select_material").val() != ''){
            check_material++;
        }
        if($(this).find(".select_materialWarehouse").val() == ''){
            check_warehouse++;
        }

        if(material_array.indexOf($(this).find(".select_material").val()+','+$(this).find(".select_materialWarehouse").val()) >= 0){
            check_same_material_and_warehouse++;
            same_material_and_warehouse += $(this).find(".get_material_name").text()+'->'+$(this).find(".get_material_warehouse").text()+"\r\n";
        }
        material_array.push($(this).find(".select_material").val()+','+$(this).find(".select_materialWarehouse").val());

        if($(this).find(".materialAmount").val() != ''){
            check_quantity++

        }

        if(isNaN($(this).find(".materialAmount").val())) {
            check_quantity_number++;
        }
    });
    if(check_material == 0){
        $('#error_material').click();
        return;
    }
    if(check_warehouse > 0){
        $('#error_warehouse').click();
        return;
    }
    if(check_quantity == 0){
        $('#error_quantity').click();
        return;
    }
    // if(check_calculate > 0){
    //     $('#error_calculate').click();
    //     return;
    // }
    if(check_quantity_number > 0){
        $('#error_quantity_number').click();
        return;
    }
    var check_amount = 0;
    $(".materialRow").each(function(index, el) {
        if($(this).find(".select_material").val() != ''){
            if($(this).find(".materialAmount").val() <= 0){
                check_amount++;
            }
        }
    });
    if(check_amount > 0){
        $('#error_amount').click();
        return;
    }
    if(check_same_material_and_warehouse > 0){
        swal({
            title: "選擇的物料與倉儲有重複，是否繼續存擋",
            text: same_material_and_warehouse,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: '確定',
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {
            $("#buy_to_stock_from").submit();

        });
        return;
    } else {
        $("#buy_to_stock_from").submit();


    }



}
</script>
@endsection
