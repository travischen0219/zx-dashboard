@extends('layouts.app')

@section('title','編輯入庫')

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

    html input[disabled]{
        cursor: not-allowed;
    }
    html select[disabled]{
        cursor: not-allowed;
    }
    .get_material_warehouse{
        color:#fff;
        background-color: #75afe6;
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
    <h1 class="page-title"> 編輯入庫
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
                <form role="form" action="{{ route('stock.update',$stock->id) }}" method="POST" id="stock_from">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $stock->lot_number }}" disabled>
                                    <label for="lot_number" style="color:#248ff1;">批號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                供應商 :
                                <button type="button" class="btn blue">{{ $stock->supplier_name->code.' '.$stock->supplier_name->shortName}}</button>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="stock_date" class="form-control" autocomplete="off" id="datepicker01">
                                    <label for="datepicker01" style="color:#248ff1;">入庫日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                採購單號 : <span style="color:#000">P{{$stock->buy_no}}</span>
                            </div>
                        </div>

                        <div class="col-md-12">



                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo">{{ old('memo') }}</textarea>
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
                                                    <th width="15%"> 入庫方式 </th>
                                                    <th width="30%"> 物料 </th>
                                                    <th width="10%"> 單位 </th>
                                                    <th width="20%"> 倉儲位置 </th>
                                                    {{-- <th width="15%"> 目前庫存 </th>                                                  --}}
                                                    <th width="15%"> 入庫數量 </th>

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
                            <div class="form-actions noborder">
                                <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('stock.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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

<button id="error_supplier" class="btn btn-danger mt-sweetalert" data-title="尚未選擇 供應商" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_stock_date" class="btn btn-danger mt-sweetalert" data-title="入庫日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_material" class="btn btn-danger mt-sweetalert" data-title="未選擇任何物料" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_warehouse" class="btn btn-danger mt-sweetalert" data-title="倉儲位置必選" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_quantity" class="btn btn-danger mt-sweetalert" data-title="有數量未填寫" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_amount" class="btn btn-danger mt-sweetalert" data-title="入庫數量為零或負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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

<script>

$( function() {
    $( "#datepicker01" ).datepicker();
    $( "#datepicker01" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker01" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker01" ).datepicker('setDate', "{{ $stock->stock_date }}");

});

var materialCount = $("#materialCount").val();
var currentMaterial = 0;
function addMaterial() {
    $.post(
        "{{ route('select_material_stock.addRow') }}",
        {'_token':"{{csrf_token()}}",'materialCount': materialCount,'edit':"1"},
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
    $.magnificPopup.open({
        showCloseBtn : false,
        enableEscapeKey : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{{route('selectMaterial_stock')}}"}
    });
}

function setMaterial(code,name,buy,unit,cost,price,id,warehouse_name,unit_name,stock,warehouse_id){
    $.magnificPopup.close();
    var str = code+' '+name;
    $('#materialName' + currentMaterial).text(str);
    $('#material' + currentMaterial).val(id);
    $('#materialStock_show' + currentMaterial).html(stock);
    $('#materialStock' + currentMaterial).val(stock);
    $('#materialUnit_show' + currentMaterial).html(unit_name);
    $('#materialUnit' + currentMaterial).val(unit);
    $('#materialPrice' + currentMaterial).val(price);

    if(warehouse_id > 0){
        $('#materialWarehouseName' + currentMaterial).html(warehouse_name);
        $('#materialWarehouse' + currentMaterial).val(warehouse_id);
        get_warehouse_stock(id, warehouse_id);
    } else {
        $('#materialWarehouseName' + currentMaterial).html('請選擇倉儲');
        $('#materialWarehouse' + currentMaterial).val('');
    }
    // $('#materialUnit' + currentMaterial).children().each(function(){
    //     if ($(this).val()==unit){
    //             $(this).attr("selected", "true");
    //     }
    // });
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


function submit_btn(){
    if($('#datepicker01').val() == ''){
        $('#error_stock_date').click();
        return;
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
        if((parseFloat($(this).find(".materialStock").html())+parseFloat($(this).find(".materialAmount").val())) < 0) {
            check_calculate++;
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
    if(check_calculate > 0){
        $('#error_calculate').click();
        return;
    }
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
            $("#stock_from").submit();
        });
        return;
    } else {
        $("#stock_from").submit();
    }
}
</script>
@endsection
