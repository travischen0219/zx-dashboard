@extends('layouts.app')

@section('title','新增應付帳款')

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
    <h1 class="page-title"> 新增應付帳款
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
                <form role="form" action="{{ route('account_payable.store') }}" method="POST" id="account_payable_from">
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
                                供應商 :
                                <button id="select_supplier" type="button" class="btn blue" onclick="selectSupplier();">按此選擇</button>
                                <input type="hidden" id="supplier" name="supplier">
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="buy_no" class="form-control" id="buy_no" value="{{ old('buy_no') }}">
                                    <label for="buy_no" style="color:#248ff1;">採購單號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input">
                                    <select class="form-control" id="status" name="status">
                                        <option value="1" {{old('status') == 1 ? 'selected' : ''}}>未付款</option>
                                        <option value="2" {{old('status') == 2 ? 'selected' : ''}}>已付款</option>
                                        <option value="3" {{old('status') == 3 ? 'selected' : ''}}>取消</option>

                                    </select>
                                    <label for="status" style="color:#248ff1;">狀態</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            {{-- <div class="col-md-3">
                                <div class="form-group form-md-line-input">
                                    <select class="form-control" id="mouth" name="month">
                                        @foreach($months as $month)
                                            <option value="{{$month}}" {{old('month') == $month ? 'selected' : ''}}>{{$month}}</option>
                                        @endforeach
                                    </select>
                                    <label for="month" style="color:#248ff1;">會計月份</label>
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="createDate" class="form-control" autocomplete="off" id="datepicker01" value="{{ old('createDate') }}">
                                    <label for="datepicker01" style="color:#248ff1;">開單日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="payDate" class="form-control" autocomplete="off" id="datepicker02" value="{{ old('payDate') }}">
                                    <label for="datepicker02" style="color:#248ff1;">付款日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                會計單號 : <span style="color:#000">AP{{$account_payable_no}}</span>
                                <input type="hidden" name="account_payable_no" value="{{$account_payable_no}}">
                            </div>
                        </div>

                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="total_price" class="form-control" id="total_price" readonly>
                                    <label for="total_price" style="color:#248ff1;">小計</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="payable" class="form-control" id="payable">
                                    <label for="payable" style="color:#248ff1;">應付金額</label>
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
                                        <span class="caption-subject"> 應付明細</span>
                                    </div>
                                    <div class="tools"> </div>
                                </div>
                                <div class="portlet-body">
                                    <div style="margin-left:7px;margin-bottom: 10px;">
                                        <a href="javascript:addMaterial();" class="btn btn-primary"><i class="fa fa-plus"></i> 新增物料</a>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="materialTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="10%"> 操作 </th>
                                                    <th width="30%"> 物料 </th>
                                                    <th width="15%"> 數量 </th>
                                                    <th width="15%"> 單位 </th>
                                                    <th width="15%"> 單位成本 </th>
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




                        <div class="col-md-12">
                            <div class="form-actions noborder">
                                <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('account_payable.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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
<button id="error_supplier" class="btn btn-danger mt-sweetalert" data-title="尚未選擇 供應商" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_createDate" class="btn btn-danger mt-sweetalert" data-title="開單日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_buy_no_prefix" class="btn btn-danger mt-sweetalert" data-title="採購單號開頭為 P" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_buy_no" class="btn btn-danger mt-sweetalert" data-title="採購單號長度有誤" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_payable" class="btn btn-danger mt-sweetalert" data-title="應付金額 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_payable_negative" class="btn btn-danger mt-sweetalert" data-title="應付金額 不可為負數" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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
        "{{ route('selectMaterial.addRow') }}",
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
        items:{src:"{{route('selectMaterial')}}"}
    });
}

function setMaterial(code,name,buy,unit,cost,price,id,unit_name){
    $.magnificPopup.close();
    var str = code+' '+name;
    $('#materialName' + currentMaterial).text(str);
    $('#material' + currentMaterial).val(id);
    $('#materialAmount' + currentMaterial).val(buy);
    $('#materialUnit_show' + currentMaterial).html(unit_name);
    $('#materialUnit' + currentMaterial).val(unit);
    $('#materialPrice' + currentMaterial).val(cost);

    // $('#materialUnit' + currentMaterial).children().each(function(){
    //     if ($(this).val()==unit){
    //             $(this).attr("selected", "true");
    //     }
    // });
    total();
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
    $("#total_price").val(total);
    $("#payable").val(total);
}

$(function() {
    addMaterial();
    total();
});

function submit_btn(){
    if($('#lot_number').val() == ''){
        $('#error_lot').click();
        return;
    } else if($('#supplier').val() == ''){
        $('#error_supplier').click();
        return;
    } else if($('#datepicker01').val() == ''){
        $('#error_createDate').click();
        return;
    }
    if($('#buy_no').val() != ''){
        if($('#buy_no').val().substr(0,1) != 'P'){
            $('#error_buy_no_prefix').click();
            return;
        }
        if($('#buy_no').val().length != 12){
            $('#error_buy_no').click();
            return;
        }
    }
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
    if(check_same_material > 0){
        swal({
            title: "選擇的物料有重複",
            text: same_material,
            type: "warning",
            showCancelButton: false,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: '確定',
            cancelButtonText: '取消',
            closeOnConfirm: true
        });
        return;
    }

    if($('#payable').val() == 0 || $('#payable').val() ==''){
        $('#error_payable').click();
        return;
    }
    if($('#payable').val() < 0){
        $('#error_payable_negative').click();
        return;
    }

    $("#account_payable_from").submit();
}
</script>
@endsection
