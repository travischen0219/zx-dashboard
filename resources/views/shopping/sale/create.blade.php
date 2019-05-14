@extends('layouts.app')

@section('title','新增銷貨')

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
    <h1 class="page-title"> 新增銷貨
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
                <form role="form" action="{{ route('sale.store') }}" method="POST" id="sale_from" enctype="multipart/form-data">
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
                                <div class="form-group form-md-line-input">
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" >新訂單</option>
                                        <option value="2" >新報價</option>
                                    </select>
                                    <label for="status" style="color:#248ff1;">狀態</label>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                銷貨單號 : <span style="color:#000">S{{$sale_no}}</span>
                                <input type="hidden" name="sale_no" value="{{$sale_no}}">
                            </div>
                        </div>

                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="createDate" class="form-control" autocomplete="off" id="datepicker01" value="{{ old('createDate') }}">
                                    <label for="datepicker01" style="color:#248ff1;">新增日期 (YYYY-MM-DD)</label>
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
                                                    <th width="10%"> 操作 </th>
                                                    <th width="30%"> 物料 </th>
                                                    <th width="15%"> 銷貨數量 </th>
                                                    <th width="15%"> 單位 </th>
                                                    <th width="15%"> 單位售價 </th>
                                                    <th width="15%"> 小計 </th>

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

                        {{-- show module picture start --}}
                        <div class="col-md-12">
                            <div id="module_pic">
                                <div class="col-md-12">
                                    <p style="font-size:18px;margin-top:18px;margin-left:20px;color:#248ff1;">物料模組圖片</p>
                                    <hr>
                                </div>



                            </div>
                        </div>
                        {{-- show module picture end --}}


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
                                <a href="{{ route('sale.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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
<button id="error_createDate" class="btn btn-danger mt-sweetalert" data-title="新增日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_expireDate" class="btn btn-danger mt-sweetalert" data-title="有效期限 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_material" class="btn btn-danger mt-sweetalert" data-title="未選擇任何物料" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_amount" class="btn btn-danger mt-sweetalert" data-title="出庫數量為零或負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_price" class="btn btn-danger mt-sweetalert" data-title="單價不可為負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_sale_no_prefix" class="btn btn-danger mt-sweetalert" data-title="銷貨單號開頭為 S" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_sale_no" class="btn btn-danger mt-sweetalert" data-title="銷貨單號長度有誤" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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
        "{{ route('select_material_sale.addRow') }}",
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
    $("#picRow" + id).fadeOut('fast', function() {
        $(this).remove();
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

function setMaterial(code,name,buy,unit,cost,price,id,warehouse,unit_name,stock){
    $.magnificPopup.close();
    var str = code+' '+name;
    $('#materialName' + currentMaterial).text(str);
    $('#material' + currentMaterial).val(id);
    // $('#materialStock_show' + currentMaterial).html(stock);
    // $('#materialStock' + currentMaterial).val(stock);
    $('#materialUnit_show' + currentMaterial).html(unit_name);
    // $('#materialUnit' + currentMaterial).val(unit);
    $('#materialPrice' + currentMaterial).val(price);
    // $('#materialUnit' + currentMaterial).children().each(function(){
    //     if ($(this).val()==unit){
    //             $(this).attr("selected", "true");
    //     }
    // });
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
        "{{ route('select_material_module_sale.addModule') }}",
        {'_token':"{{csrf_token()}}",'id': module_id,'materialCount': materialCount},
        function(response) {

            $("#materialTable").append(response['data']);
            $("#module_pic").append(response['pic_data']);

            total();
            materialCount = response['materialCount'];
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
            // $(this).find(".materialSubTotal_show").html("請輸入數字");
            $(this).find(".materialPriceSubTotal_show").html("請輸入數字");
        } else {
            total += subAmount * subPrice;
            $(this).find(".materialPriceSubTotal_show").html(subAmount * subPrice);

            // subTotal = subStock - subAmount;
            // $(this).find(".materialSubTotal_show").html(subTotal);
            // $(this).find(".materialSubTotal").val(subTotal);
            // if(subTotal < 0){
            //     $(this).find(".materialSubTotal_show").css("color","red");
            // } else {
            //     $(this).find(".materialSubTotal_show").css("color","black");
            // }
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
        $('#error_createDate').click();
        return;
    } else if($('#datepicker02').val() == ''){
        $('#error_expireDate').click();
        return;
    }

    // var check_material = 0;
    // $(".materialRow").each(function(index, el) {
    //     if($(this).find(".select_material").val() != ''){
    //         check_material++;
    //     }
    // });
    // if(check_material == 0){
    //     $('#error_material').click();
    //     return;
    // }

    var check_material = 0;
    var check_same_material = 0;
    var material_array = [] ;
    var same_material = '';
    $(".materialRow").each(function(index, el) {
        if($(this).find(".select_material").val() != ''){
            check_material++;
        }
        if(material_array.indexOf($(this).find(".select_material").val()) >= 0){
            check_same_material++;
            same_material += $(this).find(".get_material_name").text()+"\r\n";
        }
        material_array.push($(this).find(".select_material").val());
    });
    if(check_material == 0){
        $('#error_material').click();
        return;
    }
    // if(check_same_material > 0){
    //     swal({
    //         title: "選擇的物料有重複",
    //         text: same_material,
    //         type: "warning",
    //         showCancelButton: false,
    //         confirmButtonColor: "#DD6B55",
    //         confirmButtonText: '確定',
    //         cancelButtonText: '取消',
    //         closeOnConfirm: true
    //     });
    //     return;
    // }

    // var check_amount = 0;
    // $(".materialRow").each(function(index, el) {
    //     if($(this).find(".materialAmount").val() <= 0){
    //         check_amount++;
    //     }
    // });
    // if(check_amount > 0){
    //     $('#error_amount').click();
    //     return;
    // }

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

    // var check_stock = 0;
    // $(".materialRow").each(function(index, el) {
    //     if($(this).find(".materialSubTotal").val() < 0){
    //         check_stock++;
    //     }
    // });
    // if(check_stock > 0){

    //     swal({
    //         title: "庫存將造成負數，要繼續存檔嗎？",
    //         type: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#DD6B55",
    //         confirmButtonText: '確定',
    //         cancelButtonText: '取消',
    //         closeOnConfirm: false
    //     }, function () {
    //         $("#sale_from").submit();

    //     });

    //     return;
    // } else {
    //     $("#sale_from").submit();
    // }
    $("#sale_from").submit();

}

function show_image(path) {
    $.magnificPopup.open({
        showCloseBtn : false,
        enableEscapeKey : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        modal:false,
        type:'image',
        items:{src: path}
    });
}

</script>
@endsection
