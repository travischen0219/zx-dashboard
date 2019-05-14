@extends('layouts.app')

@section('title','修改物料模組')

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
    <h1 class="page-title"> 修改物料模組
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
                <form role="form" action="{{ route('material_module.update',$material_module->id) }}" method="POST" id="material_module_from" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}                                              
                    <div class="form-body">

                        <div class="col-md-12" style="height:90px;">

                            <div class="col-md-4">
                                <div class="form-group form-md-line-input" >
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $material_module->name }}">
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
                                    <textarea class="form-control" rows="3" name="memo" id="memo" {{ $material_module->status != 1 ? 'disabled' : ''}}>{{ $material_module->memo }}</textarea>
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
                                    <div style="margin-left:7px;margin-bottom: 10px;">
                                        <a href="javascript:addMaterial();" class="btn btn-primary" {{ $material_module->status != 1 ? 'style=display:none;' : ''}}><i class="fa fa-plus"></i> 新增物料</a>
                                    </div>
                                    
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
                                        <input type="hidden" name="total_cost" id="total_cost">
                                        <input type="hidden" name="total_price" id="total_price">
                                    </div>
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
                        </div>
                    
                        {{-- file upload start --}}
                        <div class="col-md-12">                                        
                            <div style="border: #248ff1 solid 2px;width:100%;height: 400px;">
                                <div class="col-md-12">
                                    <p style="font-size:18px;margin-top:18px;margin-left:20px;color:#248ff1;">檔案上傳<span style="color:red;">【每一檔案上傳限制5M；操作新增時勿同時做刪除舊檔案動作，可先刪除再新增，或先新增存檔再刪除，新增和刪除檔案請分開兩次處理】</span></p>
                                    <hr>
                                </div>  

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
                                                <p style="margin-top:6px;margin-bottom: 50px">
                                                    <a href="javascript:;" class="btn red pull-right btn-sm" role="button" onclick="
                                                        if(confirm('確定要刪除嗎 ?')){
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{$material_module->image_1->id}}').submit();
                                                        } else {
                                                            event.preventDefault();
                                                        }">刪除(主圖)</a>
                                                    
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
                                                <p style="margin-top:6px;margin-bottom: 50px">
                                                    <a href="javascript:;" class="btn red pull-right btn-sm" role="button" onclick="
                                                        if(confirm('確定要刪除嗎 ?')){
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{$material_module->image_2->id}}').submit();
                                                        } else {
                                                            event.preventDefault();
                                                        }">刪除</a>
                                                    
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
                                                <p style="margin-top:6px;margin-bottom: 50px">
                                                    <a href="javascript:;" class="btn red pull-right btn-sm" role="button" onclick="
                                                        if(confirm('確定要刪除嗎 ?')){
                                                            event.preventDefault();
                                                            document.getElementById('delete-form-{{$material_module->image_3->id}}').submit();
                                                        } else {
                                                            event.preventDefault();
                                                        }">刪除</a>
                                                    
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                @endif

                                @if($upload_check_1)
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
                                                        <span class="fileinput-new" style=""> 選擇檔案(主圖) </span>
                                                        <span class="fileinput-exists"> 更改 </span>
                                                        <input type="file" name="upload_image_1"> </span>
                                                    <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"> 移除 </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if($upload_check_2)
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
                                @endif
                                @if($upload_check_3)
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
                                <button type="button" class="btn" onclick="submit_btn();" style="color:#fff;background-color: #248ff1;"><i class="fa fa-check"></i> 存 檔</button>
                                <a href="{{ route('material_module.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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

@if($material_module->file_1 > 0)
    <form id="delete-form-{{$material_module->image_1->id}}" action="{{ url('settings/material_module_file/delete/1/'. $material_module->id.'/'.$material_module->image_1->id) }}" method="post" style="display:none">
        {{ csrf_field() }}
    </form>
@endif
@if($material_module->file_2 > 0)
    <form id="delete-form-{{$material_module->image_2->id}}" action="{{ url('settings/material_module_file/delete/2/'. $material_module->id.'/'.$material_module->image_2->id) }}" method="post" style="display:none">
        {{ csrf_field() }}
    </form>
@endif
@if($material_module->file_3 > 0)
    <form id="delete-form-{{$material_module->image_3->id}}" action="{{ url('settings/material_module_file/delete/3/'. $material_module->id.'/'.$material_module->image_3->id) }}" method="post" style="display:none">
        {{ csrf_field() }}
    </form>
@endif

<button id="error_lot" class="btn btn-danger mt-sweetalert" data-title="批號 必填" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_number" class="btn btn-danger mt-sweetalert" data-title="數量、成本或售價必須為數字" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_negative" class="btn btn-danger mt-sweetalert" data-title="數量、成本或售價不能為負數或零" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
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
<script src="{{asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js')}}" type="text/javascript"></script>

<script>


var materialCount = $("#materialCount").val();
var currentMaterial = 0;

function addMaterial() {
    $.post(
        "{{ route('selectMaterial_module.addRow') }}", 
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
    $('#materialCost' + currentMaterial).val(cost);    
    $('#materialPrice' + currentMaterial).val(price);

    total();    
}

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
    $("#total_cost").val(total_cost);
    $("#total_price").val(total_price);
}

$(function() {
    total();
});

function submit_btn(){
 
    var check_number = 0;
    var check_negative = 0;
    var check_material = 0;
    var check_same_material = 0;
    var material_array = [] ;
    var same_material = '';
    $(".materialRow").each(function(index, el) {
        var subAmount = $(this).find(".materialAmount").val();
        var subCost = $(this).find(".materialCost").val();
        var subPrice = $(this).find(".materialPrice").val();
        if(isNaN(subAmount) || isNaN(subCost) || isNaN(subPrice)) {
            check_number++;
        }
        if(subAmount <= 0 || subCost <= 0 || subPrice <= 0){
            check_negative++;
        }

        if($(this).find(".select_material").val() != ''){
            check_material++;
        }
        if(material_array.indexOf($(this).find(".select_material").val()) >= 0){
            check_same_material++;
            same_material += $(this).find(".get_material_name").text()+"\r\n";
        }
        material_array.push($(this).find(".select_material").val()); 
    });
    if(check_number > 0){
        $('#error_number').click();
        return;
    }
    if(check_negative > 0){
 //       $('#error_negative').click();
  //      return;
    }
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
    
    $("#material_module_from").submit();
}
</script>
@endsection
