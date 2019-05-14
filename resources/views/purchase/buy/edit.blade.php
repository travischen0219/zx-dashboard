@extends('layouts.app')

@section('title','採購修改')

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
    html select[disabled]{
        cursor: not-allowed;
    }
    #loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url("{{asset('assets/apps/img/loader_icon.gif')}}") 50% 50% no-repeat rgb(249,249,249);
        background-size:120px 120px;
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
    <h1 class="page-title"> 採購修改
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')
<div id="loader" style="display: none"></div>

<div class="row">

    <div class="col-md-12 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light">
            @include('includes.messages')

            <div class="portlet-body form">
                <form role="form" action="{{ route('buy.update',$buy->id) }}" method="POST" id="buy_from">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}                                                
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                        @if($buy->status == 1 || $buy->status == 2 || $buy->status == 3 || $buy->status == 11)
                                            <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $buy->lot_number }}">
                                        @elseif($buy->status == 4)
                                            <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $buy->lot_number }}" disabled>
                                        @endif
                                    <label for="lot_number" style="color:#248ff1;">批號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                供應商 : 
                                <button type="button" class="btn blue">{{ $buy->supplier_name->code.' '.$buy->supplier_name->shortName}}</button>
                            </div>
                            
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                採購單號 : <span style="color:#000">P{{$buy->buy_no}}</span> 
                            </div>
                           


                        </div>

                        <div class="col-md-12" style="height:90px;">

                            @if($buy->status == 1 || $buy->status == 2 || $buy->status == 3 || $buy->status == 11)
                                <div class="col-md-3">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="status" name="status">
                                            <option value="1" {{$buy->status == 1 ? 'selected' : ''}}>未採購</option>
                                            <option value="2" {{$buy->status == 2 ? 'selected' : ''}}>已採購</option>
                                            <option value="3" {{$buy->status == 3 ? 'selected' : ''}}>已到貨</option>
                                            <option value="11" {{$buy->status == 11 ? 'selected' : ''}}>轉半成品</option>
                                            <option value="4" {{$buy->status == 4 ? 'selected' : ''}}>採購轉入庫</option>
                                        </select>
                                        <label for="status" style="color:#248ff1;">採購狀態</label>
                                    </div>
                                </div>
                            @elseif($buy->status == 4)
                                <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                    採購狀態 : <span style="color:blue">採購轉入庫</span> 
                                </div>
                            @endif

                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                加工廠商 : 
                                <button id="select_manufacturer" type="button" class="btn blue" onclick="selectManufacturer();">按此選擇</button>
                                <input type="hidden" id="manufacturer" name="manufacturer">
                            </div>

                        </div>

                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="buyDate" class="form-control" autocomplete="off" id="datepicker01" value="{{ $buy->buyDate }}" {{ $buy->status == 4 ? 'disabled' : ''}}>
                                    <label for="datepicker01" style="color:#248ff1;">採購日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="expectedReceiveDate" class="form-control" autocomplete="off" id="datepicker02" value="{{ $buy->expectedReceiveDate }}" {{ $buy->status == 4 ? 'disabled' : ''}}>
                                    <label for="datepicker02" style="color:#248ff1;">預計到貨日 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="realReceiveDate" class="form-control" autocomplete="off" id="datepicker03" value="{{ $buy->realReceiveDate }}" {{ $buy->status == 4 ? 'disabled' : ''}}>
                                    <label for="datepicker03" style="color:#248ff1;">實際到貨日 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo" {{ $buy->status == 4 ? 'disabled' : ''}}>{{ $buy->memo }}</textarea>
                                    <label for="memo" style="color:#248ff1;font-size: 16px;">採購註解</label>
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
                                        @if($buy->status == 1)
                                            <a href="javascript:addMaterial();" class="btn btn-primary"><i class="fa fa-plus"></i> 新增物料</a>
                                            <a href="javascript:openMaterial_module();" class="btn btn-primary"><i class="fa fa-plus"></i> 選擇物料模組</a>                                                                               
                                        @else

                                        @endif                                                                      
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table id="materialTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="5%"> 操作 </th>
                                                    <th width="30%"> 物料 </th>
                                                    <th width="10%"> 計價數量 </th>
                                                    <th width="10%"> 計價單位 </th>
                                                    <th width="10%"> 計價價格 </th>
                                                    <th width="10%"> 入庫數量 </th>
                                                    <th width="10%"> 採購數量 </th>
                                                    <th width="5%"> 單位 </th>
                                                    <th width="10%"> 單位成本 </th>
                                                    <th width="10%"> 小計 </th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                {!! $data !!}
                                                
                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="text-right">總計：<span id="materialTotal">0</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    
                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="well">
                                <span>最後修改人員 : {{ $updated_user->fullname }} @if($updated_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span><br>
                                <span>最後修改時間 : {{ $buy->updated_at }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-actions noborder">
                                @if($buy->status == 1 || $buy->status == 2 || $buy->status == 3 || $buy->status == 11)  
                                    <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                    <a href="{{ route('buy.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                                @elseif($buy->status == 4)
                                    <a href="{{ route('buy.index') }}" class="btn" style="color:#fff;background-color: #248ff1;"><i class="fa fa-reply"></i> 返 回</a>                                    
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
<button id="error_lot" class="btn btn-danger mt-sweetalert" data-title="批號 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_buyDate" class="btn btn-danger mt-sweetalert" data-title="採購日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_expectedReceiveDate" class="btn btn-danger mt-sweetalert" data-title="預計到貨日 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_realReceiveDate" class="btn btn-danger mt-sweetalert" data-title="實際到貨日 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_material" class="btn btn-danger mt-sweetalert" data-title="未選擇任何物料" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_selectManufacturer" class="btn btn-danger mt-sweetalert" data-title="請選擇加工廠商" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_check_stock_amount" class="btn btn-danger mt-sweetalert" data-title="入庫數量必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>

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

function selectManufacturer() {
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
    $('#select_manufacturer').text(str);
    $('#manufacturer').val(id);
}

$( function() {
    $( "#datepicker01" ).datepicker();
    $( "#datepicker01" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker01" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker01" ).datepicker('setDate', "{{ $buy->buyDate }}");

    $( "#datepicker02" ).datepicker();
    $( "#datepicker02" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker02" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker02" ).datepicker('setDate', "{{ $buy->expectedReceiveDate }}");
    
    $( "#datepicker03" ).datepicker();
    $( "#datepicker03" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker03" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker03" ).datepicker('setDate', "{{ $buy->realReceiveDate }}");
    
});

var materialCount = $("#materialCount").val();
var currentMaterial = 0;



function addMaterial() {
    $.post(
        "{{ route('selectMaterial_buy.addRow') }}", 
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

function setMaterial(code,name,buy,unit,cost,price,id,unit_name,warehouse_name,warehouse_id,cal_unit,cal_price){
    $.magnificPopup.close();
    var str = code+' '+name;
    if(cal_unit == ''){
        cal_unit = 0;
    }
    $('#materialName' + currentMaterial).text(str);
    $('#material' + currentMaterial).val(id);
    $('#materialCalAmount' + currentMaterial).val(buy);
    $('#materialCalUnit' + currentMaterial).val(cal_unit);
    $('#materialCalPrice' + currentMaterial).val(cal_price);    
    $('#materialUnit_show' + currentMaterial).html(unit_name);
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
        "{{ route('selectMaterialModule_buy.addModule') }}", 
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
        
        var materialcalUnit = $(this).find(".materialcalUnit").val();
        var materialCalAmount2 = $(this).find(".materialCalAmount2").val();
        var materialCalPrice = $(this).find(".materialCalPrice").val();
        var materialCalAmount = $(this).find(".materialCalAmount").val();
        if(materialcalUnit=="7"){
            ssu = materialCalAmount2*materialCalPrice/materialCalAmount;
            ssu = round(ssu, 1);
            $(this).find(".materialPrice").val(ssu);
        }else{
            $(this).find(".materialPrice").val(materialCalPrice);
        }

        var subAmount = $(this).find(".materialAmount").val();
        var subPrice = $(this).find(".materialPrice").val();
       
        if(isNaN(subAmount) || isNaN(subPrice)) {
            $(this).find(".materialSubTotal").html("請輸入數字");
        } else {
            total += round(subAmount * subPrice,1);
            $(this).find(".materialSubTotal").html(round(subAmount * subPrice,1));
        }
    });

    $("#materialTotal").html(total);
}


$(function() {
    total();
});

function round(value, precision) {
  if (Number.isInteger(precision)) {
    var shift = Math.pow(10, precision);
    return Math.round(value * shift) / shift;
  } else {
    return Math.round(value);
  }
} 

function submit_btn(){
    if($('#lot_number').val() == ''){
        $('#error_lot').click();
        return;
    } else if($('#supplier').val() == ''){
        $('#error_supplier').click();
        return;       
    }
    // if($('#datepicker01').val() == ''){
    //     $('#error_buyDate').click();
    //     return;
    // } else if($('#datepicker02').val() == ''){
    //     $('#error_expectedReceiveDate').click();
    //     return;
    // }

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
    if($('#status').val()==11){
        if($('#manufacturer').val() == ''){
            $('#error_selectManufacturer').click();
            return;
        }
    }

    if($('#status').val()==3 || $('#status').val()==4){
        if($('#datepicker03').val() == ''){
            $('#error_realReceiveDate').click();
            return;
        }
    }

    var check_material = 0;
    var check_same_material = 0;
    var material_array = [] ;
    var same_material = '';
    var check_stock_amount = 0;
    $(".materialRow").each(function(index, el) {
        if($(this).find(".select_material").val() != ''){
            check_material++;
        }
        if(material_array.indexOf($(this).find(".select_material").val()) >= 0){
            check_same_material++;
            same_material += $(this).find(".get_material_name").text()+"\r\n";
        }
        material_array.push($(this).find(".select_material").val());

        if($(this).find(".materialAmount").val() == 0 || $(this).find(".materialAmount").val() == ''){
            check_stock_amount++;
        } 
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

    if($('#status').val()==4){
        if(check_stock_amount > 0){
            $('#error_check_stock_amount').click();
            return;
        }
    }


    $("#loader").fadeIn("slow");
    
    $("#buy_from").submit();

}

</script>
@endsection