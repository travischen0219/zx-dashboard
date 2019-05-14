@extends('layouts.app')

@section('title','修改詢價')

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

    #dis{
        cursor: not-allowed;
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
    <h1 class="page-title"> 修改詢價
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
                <form role="form" action="{{ route('inquiry.update',$inquiry->id) }}" method="POST" id="inquiry_from">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $inquiry->lot_number }}" {{ $inquiry->status != 1 ? 'readonly' : ''}}>
                                    <label for="lot_number" style="color:#248ff1;">批號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                供應商 :
                                <button type="button" class="btn blue" {{ $inquiry->status != 1 ? 'id=dis' : ''}}>{{ $inquiry->supplier_name->code.' '.$inquiry->supplier_name->shortName}}</button>
                            </div>

                            @if($inquiry->status == 1)
                                <div class="col-md-3">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="status" name="status">
                                            <option value="1" {{ $inquiry->status == 1 ? 'selected' : '' }} >未成交</option>
                                            <option value="2" {{ $inquiry->status == 2 ? 'selected' : '' }} >已成交</option>
                                        </select>
                                        <label for="status" style="color:#248ff1;">詢價狀態</label>
                                    </div>
                                </div>
                            @elseif($inquiry->status == 2)
                                <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                    狀態 : <span style="color:blue">已成交</span>
                                </div>
                            @endif

                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                詢價單號 : <span style="color:#000">R{{$inquiry->inquiry_no}}</span>
                            </div>

                        </div>
                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="askDate" class="form-control" autocomplete="off" id="datepicker01" {{ $inquiry->status != 1 ? 'disabled' : ''}}>
                                    <label for="datepicker01" style="color:#248ff1;">詢價日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="expireDate" class="form-control" autocomplete="off" id="datepicker02" {{ $inquiry->status != 1 ? 'disabled' : ''}}>
                                    <label for="datepicker02" style="color:#248ff1;">有效期限 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="dealDate" class="form-control" autocomplete="off" id="datepicker03" {{ $inquiry->status != 1 ? 'disabled' : ''}}>
                                    <label for="datepicker03" style="color:#248ff1;">成交日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo" {{ $inquiry->status != 1 ? 'disabled' : ''}}>{{ $inquiry->memo }}</textarea>
                                    <label for="memo" style="color:#248ff1;font-size: 16px;">詢價註解</label>
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
                                        @if($inquiry->status == 1)
                                            <a href="javascript:addMaterial();" class="btn btn-primary"><i class="fa fa-plus"></i> 新增物料</a>
                                            <a href="javascript:openMaterial_module();" class="btn btn-primary"><i class="fa fa-plus"></i> 選擇物料模組</a>
                                        @else

                                        @endif
                                    </div>

                                    <div class="table-responsive">
                                        <table id="materialTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="10%"> 操作 </th>
                                                    <th width="30%"> 物料 </th>
                                                    <th width="15%"> 採購數量 </th>
                                                    <th width="15%"> 計價單位 </th>
                                                    <th width="15%"> 計價價格 </th>
                                                    <th width="15%"> 小計 </th>

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
                                <span>最後修改時間 : {{ $inquiry->updated_at }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-actions noborder">
                                @if($inquiry->status == 1)
                                    <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                    <a href="{{ route('inquiry.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                                @elseif($inquiry->status == 2)
                                    <a href="{{ route('inquiry.index') }}" class="btn" style="color:#fff;background-color: #248ff1;"><i class="fa fa-reply"></i> 返 回</a>
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
<button id="error_askDate" class="btn btn-danger mt-sweetalert" data-title="詢價日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_expireDate" class="btn btn-danger mt-sweetalert" data-title="有效期限 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_dealDate" class="btn btn-danger mt-sweetalert" data-title="成交日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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
    $( "#datepicker01" ).datepicker('setDate', "{{ $inquiry->askDate }}");

    $( "#datepicker02" ).datepicker();
    $( "#datepicker02" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker02" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker02" ).datepicker('setDate', "{{ $inquiry->expireDate }}");


    $( "#datepicker03" ).datepicker();
    $( "#datepicker03" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker03" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker03" ).datepicker('setDate', "{{ $inquiry->dealDate }}");

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

    $("#materialTotal").html(total);
}

$(function() {
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
        $('#error_askDate').click();
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

    if($('#status').val()==2){
        if($('#datepicker03').val() == ''){
            $('#error_dealDate').click();
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

    $("#inquiry_from").submit();
}
</script>
@endsection
