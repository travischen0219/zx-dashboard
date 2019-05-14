@extends('layouts.app')

@section('title','新增申請出庫')

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
    <h1 class="page-title"> 新增申請出庫
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
                <form role="form" action="{{ route('apply_out_stock.store') }}" method="POST" id="apply_from" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ old('lot_number') }}">
                                    <label for="lot_number" style="color:#248ff1;">批號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                客戶名稱 :
                                <button id="select_customer" type="button" class="btn blue" onclick="selectCustomer();">按此選擇</button>
                                <input type="hidden" id="customer" name="customer">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="sale_no" class="form-control" id="sale_no" value="{{ old('sale_no') }}">
                                    <label for="sale_no" style="color:#248ff1;">銷貨單號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                狀態 : <span style="color:#000">申請中</span>
                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="applyDate" class="form-control" autocomplete="off" id="datepicker01" value="{{ old('applyDate') }}">
                                    <label for="datepicker01" style="color:#248ff1;">申請日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="expireDate" class="form-control" autocomplete="off" id="datepicker02" value="{{ old('expireDate') }}">
                                    <label for="datepicker02" style="color:#248ff1;">有效期限 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                申請出庫單號 : <span style="color:#000">A{{$apply_no}}</span>
                                <input type="hidden" name="apply_no" value="{{$apply_no}}">
                            </div>

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
                                        <a href="javascript:addMaterial();" class="btn btn-primary"><i class="fa fa-plus"></i> 新增物料</a>
                                        <a href="javascript:openMaterial_module();" class="btn btn-primary"><i class="fa fa-plus"></i> 選擇物料模組</a>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="materialTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="8%"> 操作 </th>
                                                    <th width="24%"> 物料 </th>
                                                    <th width="8%"> 單位 </th>
                                                    <th width="10%"> 倉儲 </th>
                                                    <th width="10%"> 目前庫存 </th>
                                                    <th width="10%"> 出庫數量 </th>
                                                    <th width="10%"> 剩餘庫存 </th>
                                                    <th width="10%"> 單位售價 </th>
                                                    <th width="10%"> 小計 </th>

                                                </tr>
                                            </thead>
                                            <tbody>


                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="text-right">總計：<span id="materialTotal">0</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>

                        {{-- file upload start --}}
                        <div class="col-md-12">
                            <div style="border: #248ff1 solid 2px;width:100%;height: 400px;">
                                <div class="col-md-12">
                                    <p style="font-size:18px;margin-top:18px;margin-left:20px;color:#248ff1;">檔案上傳<span style="color:red;">【每一檔案上傳限制5M】</span></p>
                                    <hr>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="text" name="name_1" class="form-control" id="name_1" value="{{ old('name_1') }}">
                                            <label for="name_1">名稱</label>
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
                                                    <span class="fileinput-new" style=""> 選擇檔案 </span>
                                                    <span class="fileinput-exists"> 更改 </span>
                                                    <input type="file" name="upload_image_1"> </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> 移除 </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="text" name="name_2" class="form-control" id="name_2" value="{{ old('name_2') }}">
                                            <label for="name_2">名稱</label>
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
                                                    <span class="fileinput-new" style=""> 選擇檔案 </span>
                                                    <span class="fileinput-exists"> 更改 </span>
                                                    <input type="file" name="upload_image_2"> </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> 移除 </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <input type="text" name="name_3" class="form-control" id="name_3" value="{{ old('name_3') }}">
                                            <label for="name_3">名稱</label>
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
                                                    <span class="fileinput-new" style=""> 選擇檔案 </span>
                                                    <span class="fileinput-exists"> 更改 </span>
                                                    <input type="file" name="upload_image_3"> </span>
                                                <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> 移除 </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- file upload end --}}


                        <div class="col-md-12">
                            <div class="form-actions noborder">
                                <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('apply_out_stock.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>
<button id="error_lot" class="btn btn-danger mt-sweetalert" data-title="批號 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_customer" class="btn btn-danger mt-sweetalert" data-title="尚未選擇 客戶" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_applyDate" class="btn btn-danger mt-sweetalert" data-title="申請日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_expireDate" class="btn btn-danger mt-sweetalert" data-title="有效期限 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_material" class="btn btn-danger mt-sweetalert" data-title="未選擇任何物料" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_amount" class="btn btn-danger mt-sweetalert" data-title="數量為零或負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_price" class="btn btn-danger mt-sweetalert" data-title="單價不可為負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_sale_no_prefix" class="btn btn-danger mt-sweetalert" data-title="銷貨單號開頭為 S" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_sale_no" class="btn btn-danger mt-sweetalert" data-title="銷貨單號長度有誤" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>

<button id="error_warehouse" class="btn btn-danger mt-sweetalert" data-title="倉儲位置必選" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_quantity" class="btn btn-danger mt-sweetalert" data-title="有數量未填寫" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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

function selectCustomer() {
    $.magnificPopup.open({
        showCloseBtn : false,
        enableEscapeKey : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{{route('selectCustomer')}}"}
    });
}

function setCustomer(code,name,id){
    $.magnificPopup.close();
    var str = code+' '+name;
    $('#select_customer').text(str);
    $('#customer').val(id);
}

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

var materialCount = 0;
var currentMaterial = 0;
function addMaterial() {
    $.post(
        "{{ route('select_material_apply_out.addRow') }}",
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
    $.magnificPopup.open({
        showCloseBtn : false,
        enableEscapeKey : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{{route('selectMaterial_inventory')}}"}
    });
}

function setMaterial(code,name,buy,unit,cost,price,id,warehouse,unit_name,stock,warehouse_id,warehouse_name){
    $.magnificPopup.close();
    var str = code+' '+name;
    $('#materialName' + currentMaterial).text(str);
    $('#material' + currentMaterial).val(id);
    // $('#materialStock_show' + currentMaterial).html(stock);
    // $('#materialStock' + currentMaterial).val(stock);
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

    total();
}

function openMaterial_module() {
    $.magnificPopup.open({
        showCloseBtn : false,
        enableEscapeKey : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{{route('selectMaterial_module_inventory')}}"}
    });
}

function setMaterial_module(module_id){
    $.magnificPopup.close();
    $.post(
        "{{ route('select_material_module_apply_out.addModule') }}",
        {'_token':"{{csrf_token()}}",'id': module_id,'materialCount': materialCount},
        function(response) {

            $("#materialTable").append(response['data']);
            total();
            materialCount = response['materialCount'];
        }
    );
}

function openSelectWarehouse(id) {
    currentMaterial = id;
    if($('#material' + currentMaterial).val()){
        var material_id = $('#material' + currentMaterial).val();
        var url_str = 'settings/selectWarehouse_byMaterial/'+material_id;
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
    var material_id = $('#material' + currentMaterial).val();
    get_warehouse_stock(material_id, id);

}

function get_warehouse_stock(material_id,warehouse_id){

    $.post(
        "{{ route('get_warehouse_stock') }}",
        {'_token':"{{csrf_token()}}",'material_id': material_id, 'warehouse_id': warehouse_id},
        function(response) {
            $('#materialStock_show' + currentMaterial).html(response);
            $('#materialStock' + currentMaterial).val(response);
            total();
        }
    );
}

function total() {
    var total = 0;
    $(".materialRow").each(function(index, el) {
        var subTotal = 0;
        var subStock = $(this).find(".materialStock").val();
        var subAmount = $(this).find(".materialAmount").val();
        var subPrice = $(this).find(".materialPrice").val();

        if(isNaN(subAmount) || isNaN(subPrice)) {
            $(this).find(".materialSubTotal_show").html("請輸入數字");
            $(this).find(".materialPriceSubTotal_show").html("請輸入數字");
        } else {
            total += subAmount * subPrice;
            $(this).find(".materialPriceSubTotal_show").html(subAmount * subPrice);

            subTotal = subStock - subAmount;
            $(this).find(".materialSubTotal_show").html(subTotal);
            $(this).find(".materialSubTotal").val(subTotal);
            if(subTotal < 0){
                $(this).find(".materialSubTotal_show").css("color","red");
            } else {
                $(this).find(".materialSubTotal_show").css("color","black");
            }
        }
    });
    $("#materialTotal").html(total);
}

$(function() {
    total();
});

function submit_btn(){
    if($('#lot_number').val() == ''){
        $('#error_lot').click();
        return;
    } else if($('#customer').val() == ''){
        $('#error_customer').click();
        return;
    } else if($('#datepicker01').val() == ''){
        $('#error_applyDate').click();
        return;
    } else if($('#datepicker02').val() == ''){
        $('#error_expireDate').click();
        return;
    }


    if($('#sale_no').val() != ''){
        if($('#sale_no').val().substr(0,1) != 'S'){
            $('#error_sale_no_prefix').click();
            return;
        }
        if($('#sale_no').val().length != 12){
            $('#error_sale_no').click();
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

    var check_price = 0;
    $(".materialRow").each(function(index, el) {
        if($(this).find(".materialPrice").val() < 0){
            check_price++;
        }
    });
    if(check_price > 0){
        $('#error_price').click();
        return;
    }

    var check_stock = 0;
    $(".materialRow").each(function(index, el) {
        if($(this).find(".materialSubTotal").val() < 0){
            check_stock++;
        }
    });
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

            if(check_stock > 0){

                swal({
                    title: "庫存將造成負數，要繼續存檔嗎？",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: '確定',
                    cancelButtonText: '取消',
                    closeOnConfirm: false
                }, function () {
                    $("#apply_from").submit();
                });
                return;
            } else {
                $("#apply_from").submit();
            }
        });
        return;
    } else {
        if(check_stock > 0){

            swal({
                title: "庫存將造成負數，要繼續存檔嗎？",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: '確定',
                cancelButtonText: '取消',
                closeOnConfirm: false
            }, function () {
                $("#apply_from").submit();
            });

            return;
        } else {
            $("#apply_from").submit();
        }
    }

}

</script>
@endsection
