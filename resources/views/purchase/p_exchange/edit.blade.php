@extends('layouts.app')

@section('title','修改換貨')

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
    <h1 class="page-title"> 修改換貨
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
                <form role="form" action="{{ route('p_exchange.update',$exchange->id) }}" method="POST" id="buy_from">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >

                                        <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $exchange->lot_number }}" disabled>

                                    <label for="lot_number" style="color:#248ff1;">批號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                供應商 :
                                <button type="button" class="btn blue">{{ $exchange->supplier_name->code.' '.$exchange->supplier_name->shortName}}</button>
                            </div>

                            @if($exchange->status == 1)
                                <div class="col-md-3">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="status" name="status">
                                            <option value="1" >換貨中</option>
                                            <option value="2" >換貨完成</option>
                                        </select>
                                        <label for="status" style="color:#248ff1;">換貨狀態</label>
                                    </div>
                                </div>
                            @elseif($exchange->status == 2)
                                <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                    換貨狀態 : <span style="color:blue">換貨完成</span>
                                </div>
                            @endif


                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                採購單號 : <span style="color:#000">P{{$exchange->buy_no}}</span>
                            </div>

                        </div>
                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="exchangeDate" class="form-control" autocomplete="off" id="datepicker01" {{ $exchange->status == 2 ? 'disabled' : ''}}>
                                    <label for="datepicker01" style="color:#248ff1;">換貨日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="realExchangeDate" class="form-control" autocomplete="off" id="datepicker02" {{ $exchange->status == 2 ? 'disabled' : ''}}>
                                    <label for="datepicker02" style="color:#248ff1;">換貨完成日 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo" {{ $exchange->status == 2 ? 'disabled' : ''}}>{{ $exchange->memo }}</textarea>
                                    <label for="memo" style="color:#248ff1;font-size: 16px;">換貨註解</label>
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
                                                    <th width="5%"> 操作 </th>
                                                    <th width="30%"> 物料 </th>
                                                    <th width="10%"> 單位 </th>
                                                    <th width="10%"> 原採購量 </th>
                                                    <th width="15%"> 換貨數量(0表示無須換貨) </th>
                                                    <th width="15%"> 單位成本 </th>
                                                    <th width="15%"> 小計 </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                {!! $data !!}

                                            </tbody>
                                        </table>
                                        <hr>
                                        {{-- <div class="text-right">總計：<span id="materialTotal">0</span></div> --}}
                                    </div>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>

                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="well">
                                <span>最後修改人員 : {{ $updated_user->fullname }} @if($updated_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span><br>
                                <span>最後修改時間 : {{ $exchange->updated_at }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-actions noborder">
                                @if($exchange->status == 1)
                                    <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                    <a href="{{ route('p_exchange.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                                @elseif($exchange->status == 2)
                                    <a href="{{ route('p_exchange.index') }}" class="btn" style="color:#fff;background-color: #248ff1;"><i class="fa fa-reply"></i> 返 回</a>
                                @endif
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

<button id="error_returnDate" class="btn btn-danger mt-sweetalert" data-title="換貨日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_realReturnDate" class="btn btn-danger mt-sweetalert" data-title="換貨完成日 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_check_calculate" class="btn btn-danger mt-sweetalert" data-title="換貨數量超過採購數量" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>


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
    $( "#datepicker01" ).datepicker('setDate', "{{ $exchange->exchangeDate }}");

    $( "#datepicker02" ).datepicker();
    $( "#datepicker02" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker02" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker02" ).datepicker('setDate', "{{ $exchange->realExchangeDate }}");



});

var materialCount = $("#materialCount").val();
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
        "{{ route('select_material_module_purchase.addModule') }}",
        {'_token':"{{csrf_token()}}",'id': module_id,'materialCount': materialCount},
        function(response) {

            $("#materialTable").append(response['data']);
            total();
            materialCount = response['materialCount'];
        }
    );
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

    // $("#materialTotal").html(total);
}

$(function() {
    total();
});

function submit_btn(){

    if($('#datepicker01').val() == ''){
        $('#error_returnDate').click();
        return;
    }

    if($('#status').val() == 2){
        if($('#datepicker02').val() == ''){
            $('#error_realReturnDate').click();
            return;
        }
    }

    var check_calculate = 0;
    $(".materialRow").each(function(index, el) {
        if(($(this).find(".materialAmount_show").text() - $(this).find(".materialAmount").val()) < 0 ){
            check_calculate++;
        }
    });
    if(check_calculate > 0){
        $('#error_check_calculate').click();
        return;
    }

    $("#buy_from").submit();

}
</script>
@endsection
