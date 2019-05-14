@extends('layouts.app')

@section('title','修改盤點')

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
    <h1 class="page-title"> 修改盤點
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
                <form role="form" action="{{ route('inventory.update',$inquiry->id) }}" method="POST" id="inquiry_from">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}                                                
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="lot_number" class="form-control" id="lot_number" value="{{ $inquiry->lot_number }}">
                                    <label for="lot_number" style="color:#248ff1;">盤點名稱</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                           

                            @if($inquiry->status == 1)
                                <div class="col-md-3">
                                    <div class="form-group form-md-line-input">
                                        <select class="form-control" id="status" name="status">
                                            <option value="1" {{ $inquiry->status == 1 ? 'selected' : '' }} >未盤點</option>
                                            <option value="2" {{ $inquiry->status == 2 ? 'selected' : '' }} >已盤點</option>
                                        </select>
                                        <label for="status" style="color:#248ff1;">盤點狀態</label>
                                    </div>
                                </div>
                            @elseif($inquiry->status == 2)
                                <div class="col-md-3" style="font-size: 16px;color:#248ff1;line-height: 50px;">
                                    狀態 : <span style="color:blue">已盤點</span> 
                                </div>
                            @endif

                            

                        </div>
                        <div class="col-md-12">

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="inventory_sdate" class="form-control" autocomplete="off" id="datepicker01" value="{{ $inquiry->inventory_sdate }}" {{ $inquiry->status != 1 ? 'disabled' : ''}}>
                                    <label for="datepicker01" style="color:#248ff1;">盤點開始日期 (YYYY-MM-DD)</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="inventory_edate" class="form-control" autocomplete="off" id="datepicker02" value="{{ $inquiry->inventory_edate }}" {{ $inquiry->status != 1 ? 'disabled' : ''}}>
                                    <label for="datepicker02" style="color:#248ff1;">盤點結束日期 (YYYY-MM-DD)</label>
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
                                    <a href="{{ route('inventory.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                                @elseif($inquiry->status == 2)
                                    <a href="{{ route('inventory.index') }}" class="btn" style="color:#fff;background-color: #248ff1;"><i class="fa fa-reply"></i> 返 回</a>                                    
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
<button id="error_lot" class="btn btn-danger mt-sweetalert" data-title="盤點名稱 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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



$( function() {
    $( "#datepicker01" ).datepicker();
    $( "#datepicker01" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker01" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker01" ).datepicker('setDate', "{{ $inquiry->inventory_sdate }}");

    $( "#datepicker02" ).datepicker();
    $( "#datepicker02" ).datepicker('option', "dateFormat", "yy-mm-dd");
    $( "#datepicker02" ).datepicker('option', 'firstDay', 1);
    $( "#datepicker02" ).datepicker('setDate', "{{ $inquiry->inventory_edate }}");
    

       
});

function submit_btn(){
   
   
    $("#inquiry_from").submit();
}
</script>
@endsection