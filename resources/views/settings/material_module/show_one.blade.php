@extends('layouts.app')

@section('title','物料模組')

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

    button[type=submit]{
        color:#fff;
        background-color: #248ff1;
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
    <h1 class="page-title"> 物料模組
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
                                                            
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">
                            <div class="col-md-4">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $material_module->name }}" disabled>
                                    <label for="name" style="color:#248ff1;">名稱</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="code" class="form-control" id="code" value="{{ $material_module->code }}" disabled="disabled">
                                    <label for="lot_number" style="color:#248ff1;">編號</label>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                           
                        </div>
                        <div class="col-md-12">


                            <div class="col-md-12">
                                <div class="form-group form-md-line-input">
                                    <textarea class="form-control" rows="3" name="memo" id="memo" readonly>{{ $material_module->memo }}</textarea>
                                    <label for="memo" style="color:#248ff1;font-size: 16px;">產品說明</label>
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
                                    
                                    <div class="table-responsive">
                                        <table id="materialTable" class="table">
                                            <thead>
                                                <tr>
                                                    <th width="10%"> 操作 </th>
                                                    <th width="30%"> 物料 </th>
                                                    <th width="10%"> 數量 </th>
                                                    <th width="10%"> 單位 </th>
                                                    <th width="10%"> 單位成本 </th>
                                                    <th width="10%"> 成本小計 </th>
                                                    <th width="10%"> 單位售價 </th>
                                                    <th width="10%"> 售價小計 </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {!! $data !!}
                                                
                                            </tbody>
                                        </table>
                                        <hr>
                                        <div class="text-right">成本總計：<span id="materialTotal_cost">0</span> , 售價總計：<span id="materialTotal_price">0</span></div>
                                    </div>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    
                        {{-- file upload start --}}
                        <div class="col-md-12">                                        
                            <div style="border: #248ff1 solid 2px;width:100%;height: 400px;">
                                <div class="col-md-12">
                                    <p style="font-size:18px;margin-top:18px;margin-left:20px;color:#248ff1;">檔案</p>
                                    <hr>
                                </div>  
                                @if($material_module->file_1 >0 || $material_module->file_2 >0 ||$material_module->file_3 >0)
                                    @if($material_module->file_1 >0)
                                        <div class="col-md-4">
                                            <div class="thumbnail" style="width:180px;">
                                                @if($material_module->image_1->thumb_name == "file_image.jpg")
                                                    <img src="{{asset('assets/apps/img/'.$material_module->image_1->thumb_name)}}" alt="{{$material_module->image_1->name}}">
                                                @else
                                                    <img src="{{asset('upload/'.$material_module->image_1->thumb_name)}}" alt="{{$material_module->image_1->name}}">
                                                @endif
                                                <div class="caption">
                                                    <h4 class="image_name">{{$material_module->image_1->name}}</h4>
                                                    <p style="margin-top:6px;">
                                                        @if($material_module->image_1->thumb_name != "file_image.jpg")
                                                            <a href="javascript:show_image('{{asset('upload/'.$material_module->image_1->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽(主圖)</a> 
                                                        @endif
                                                        <a href="{{ url('settings/file_download',$material_module->image_1->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($material_module->file_2 >0)
                                        <div class="col-md-4">
                                            <div class="thumbnail" style="width:180px;">
                                                @if($material_module->image_2->thumb_name == "file_image.jpg")
                                                    <img src="{{asset('assets/apps/img/'.$material_module->image_2->thumb_name)}}" alt="{{$material_module->image_2->name}}">
                                                @else
                                                    <img src="{{asset('upload/'.$material_module->image_2->thumb_name)}}" alt="{{$material_module->image_2->name}}">
                                                @endif
                                                <div class="caption">
                                                    <h4 class="image_name">{{$material_module->image_2->name}}</h4>
                                                    <p style="margin-top:6px;">
                                                        @if($material_module->image_2->thumb_name != "file_image.jpg")
                                                            <a href="javascript:show_image('{{asset('upload/'.$material_module->image_2->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽</a> 
                                                        @endif
                                                        <a href="{{ url('settings/file_download',$material_module->image_2->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if($material_module->file_3 >0)
                                        <div class="col-md-4">
                                            <div class="thumbnail" style="width:180px;">
                                                @if($material_module->image_3->thumb_name == "file_image.jpg")
                                                    <img src="{{asset('assets/apps/img/'.$material_module->image_3->thumb_name)}}" alt="{{$material_module->image_3->name}}">
                                                @else
                                                    <img src="{{asset('upload/'.$material_module->image_3->thumb_name)}}" alt="{{$material_module->image_3->name}}">
                                                @endif
                                                <div class="caption">
                                                    <h4 class="image_name">{{$material_module->image_3->name}}</h4>
                                                    <p style="margin-top:6px;">
                                                        @if($material_module->image_3->thumb_name != "file_image.jpg")
                                                            <a href="javascript:show_image('{{asset('upload/'.$material_module->image_3->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽</a> 
                                                        @endif
                                                        <a href="{{ url('settings/file_download',$material_module->image_3->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <p style="margin-left:20px;font-size: 18px;">無上傳檔案</p>
                                @endif
                            </div>
                        </div>
                        {{-- file upload end --}}


                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="well">
                                <span>最後修改人員 : {{ $updated_user->fullname }} @if($updated_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span><br>
                                <span>最後修改時間 : {{ $material_module->updated_at }}</span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-actions noborder">
                                <a href="{{ route('material_module.index') }}" class="btn blue"><i class="fa fa-reply"></i> 返 回</a>
                            </div>

                        </div>
                    </div>

                <input type="hidden" id="materialCount" value="{{ $materialCount }}">
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>

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

function total() {
    var total_cost = 0;
    var total_price = 0;

    $(".materialRow").each(function(index, el) {
        var subTotal = 0;
        var subAmount = $(this).find(".materialAmount").val();
        var subCost = $(this).find(".materialCost").val();        
        var subPrice = $(this).find(".materialPrice").val();

        if(isNaN(subAmount) || isNaN(subCost) || isNaN(subPrice)) {
            $(this).find(".materialSubTotal_cost").html("請輸入數字");
            $(this).find(".materialSubTotal_price").html("請輸入數字");
        } else if(subAmount <= 0 || subCost <= 0 || subPrice <= 0){
            $(this).find(".materialSubTotal_cost").html("不可為負數或零");
            $(this).find(".materialSubTotal_price").html("不可為負數或零");
        } else {
            total_cost += subAmount * subCost;
            $(this).find(".materialSubTotal_cost").html(subAmount * subCost);
            total_price += subAmount * subPrice;
            $(this).find(".materialSubTotal_price").html(subAmount * subPrice);
        }
    });

    $("#materialTotal_cost").html(total_cost);
    $("#materialTotal_price").html(total_price);
}

$(function() {
    total();
});

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