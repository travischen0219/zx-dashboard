@extends('layouts.app')

@section('title','修改應付帳款')

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
    <h1 class="page-title"> 修改應付帳款
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
                <form role="form" action="{{ route('account_payable.update',$account_payable->id) }}" method="POST" id="account_payable_from">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $account_payable->lot_number }}" readonly>
                                    <label for="lot_number" style="color:#248ff1;">批號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                供應商 :
                                <button type="button" class="btn blue">{{ $account_payable->supplier_name->code.' '.$account_payable->supplier_name->shortName}}</button>

                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    @if($account_payable->buy_no != '')
                                        <input type="text" name="buy_no" class="form-control" id="buy_no" value="P{{ $account_payable->buy_no }}" readonly>
                                    @else
                                        <input type="text" name="buy_no" class="form-control" id="buy_no" @if($account_payable->status == 2 || $account_payable->status== 3) readonly @endif>
                                    @endif
                                    <label for="buy_no" style="color:#248ff1;">採購單號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input">
                                    <select class="form-control" id="status" name="status" @if($account_payable->status == 2 || $account_payable->status== 3) disabled @endif>
                                        <option value="1" {{$account_payable->status == 1 ? 'selected' : ''}}>未付款</option>
                                        <option value="2" {{$account_payable->status == 2 ? 'selected' : ''}}>已付款</option>
                                        <option value="3" {{$account_payable->status == 3 ? 'selected' : ''}}>取消</option>

                                    </select>
                                    <label for="status" style="color:#248ff1;">狀態</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            {{-- <div class="col-md-3">
                                <div class="form-group form-md-line-input">
                                    <select class="form-control" id="mouth" name="month" @if($account_payable->status == 2 || $account_payable->status== 3) disabled @endif>
                                        @foreach($months as $month)
                                            <option value="{{$month}}" {{$account_payable->account_month == $month ? 'selected' : ''}}>{{$month}}</option>
                                        @endforeach
                                    </select>
                                    <label for="month" style="color:#248ff1;">會計月份</label>
                                </div>
                            </div> --}}
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="createDate" class="form-control" autocomplete="off" id="datepicker01"  disabled>
                                    <label for="datepicker01" style="color:#248ff1;">開單日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="payDate" class="form-control" autocomplete="off" id="datepicker02"  @if($account_payable->status == 2 || $account_payable->status== 3) disabled @endif>
                                    <label for="datepicker02" style="color:#248ff1;">付款日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                會計單號 : <span style="color:#000">AP{{$account_payable->account_payable_no}}</span>
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
                                    <input type="text" name="payable" class="form-control" id="payable" value="" @if($account_payable->status == 2 || $account_payable->status== 3) readonly @endif>
                                    <label for="payable" style="color:#248ff1;">應付金額</label>
                                    <span class="help-block"></span>
                                </div>
                                <input type="hidden" name="has_payable" class="form-control" id="has_payable" value="{{ $account_payable->total }}">

                            </div>

                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo" @if($account_payable->status == 2 || $account_payable->status== 3) readonly @endif>{{ $account_payable->memo }}</textarea>
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
                                <span>最後修改時間 : {{ $account_payable->updated_at }}</span><br>
                                <span>建立人員 : {{ $created_user->fullname }} @if($created_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-actions noborder">
                                @if($account_payable->status == 1)
                                    <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                    <a href="{{ route('account_payable.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                                @elseif($account_payable->status == 2 || $account_payable->status == 3)
                                    <a href="{{ route('account_payable.index') }}" class="btn" style="color:#fff;background-color: #248ff1;"><i class="fa fa-reply"></i> 返 回</a>
                                @endif

                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>


<button id="error_payDate" class="btn btn-danger mt-sweetalert" data-title="付款日期 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_buy_no_prefix" class="btn btn-danger mt-sweetalert" data-title="採購單號開頭為 P" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_buy_no" class="btn btn-danger mt-sweetalert" data-title="採購單號長度有誤" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_payable" class="btn btn-danger mt-sweetalert" data-title="應付金額 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_payable_negative" class="btn btn-danger mt-sweetalert" data-title="應付金額 不可為負值" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>

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
    $( "#datepicker01" ).datepicker('setDate', "{{ $account_payable->createDate }}");

    $( "#datepicker02" ).datepicker();
    $( "#datepicker02" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker02" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker02" ).datepicker('setDate', "{{ $account_payable->payDate }}");

});



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

    if($("#has_payable").val() == 0 || $("#has_payable").val() == ''){
        $("#payable").val(total);
    } else {
        $("#payable").val($("#has_payable").val());
    }
}

$(function() {
    total();
});

function submit_btn(){

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

    if($('#status').val() == 2){
        if($('#datepicker02').val() == ''){
            $('#error_payDate').click();
            return;
        }
    }

    if($('#payable').val() == 0 || $('#payable').val() =='' || $('#payable').val() < 0){
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
