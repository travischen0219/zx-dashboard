@extends('b4.app')

@section('title','物料建立')
@section('page-header')
    <i class="fas fa-puzzle-piece active-color mr-1"></i>基本資料 - 物料建立
@endsection

@section('css')
    <style>
    .table_title {
        font-size: 16px;
        line-height: 37px;
        color: #248ff1;
        text-align: right;
    }
    .table_content {
        font-size: 16px;
        line-height: 70px;
        color: #000;
        text-align: left;
    }
    .form-group.input-material {
        margin-top: 0;
        margin-bottom: 0;
    }
    .material_code {
        font-size: 16px;
        line-height: 37px;
        color: #000;
        text-align: center;
        width: 50px;
    }
    </style>
@endsection

@section('content')

    @include('includes.messages')

    <form role="form" action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

        {{-- 分類 --}}
        <div class="row">
            <div class="col-md-2 table_title">
                <span ><span style="color:red;">*</span>分類 : </span>
            </div>
            <div class="col-md-3">
                <div class="form-group form-md-line-input">
                    <select class="form-control" id="material_category" name="material_category" onchange="showFullCode();">
                        <option value="" {{ old('material_category') == '' ? 'selected' : '' }}>請選擇</option>
                        @foreach($material_categories as $cate)
                            <option value="{{$cate->code}}" {{ old('material_category') == $cate->code ? 'selected' : '' }}>[ {{$cate->code}} ] {{$cate->name}} </option>
                        @endforeach
                    </select>
                    <label for="material_category" style="color:#248ff1;"></label>
                </div>
            </div>
        </div>

        {{-- 物料編號 --}}
        <div class="row">
            <div class="col-md-2 table_title">
                <span ><span style="color:red;">*</span>物料編號 : </span>
            </div>

            <div class="col-md-2">
                <div class="form-group form-md-line-input input-material">
                    <input type="text" name="code_1" maxlength="3" class="form-control" id="code_1" onkeyup="showFullCode();" onchange="showFullCode();" value="{{ old('code_1') }}">
                    <label for="code_1">選填</label>
                    <span class="help-block"></span>
                </div>
            </div>

            <div class="col-md-1 material_code">
                <span><b>-</b></span>
            </div>

            <div class="col-md-2">
                <div class="form-group form-md-line-input input-material">
                    <input type="text" name="code_2" maxlength="3" class="form-control" id="code_2" onkeyup="showFullCode();" onchange="showFullCode();" value="{{ old('code_2') }}">
                    <label for="code_2"></label>
                    <span class="help-block"></span>
                </div>
            </div>

            <div class="col-md-1 material_code">
                <span><b>-</b></span>
            </div>

            <div class="col-md-2">
                <div class="form-group form-md-line-input input-material">
                    <input type="text" name="code_3" maxlength="5" class="form-control" id="code_3" onkeyup="showFullCode();" onchange="showFullCode();" value="{{ old('code_3') }}">
                    <label for="code_3">選填</label>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        {{-- 完整編號 --}}
        <div class="row">
            <div class="col-md-2 table_title" style="line-height: 70px;">
                <span >完整編號 : </span>
            </div>
            <div class="col-md-5 table_content">
                <span id="fullCode"></span>
            </div>
        </div>

        {{-- 品名 --}}
        <div class="row">
            <div class="col-md-2 table_title">
                <span ><span style="color:red;">*</span>品名 : </span>
            </div>

            <div class="col-md-8">
                <div class="form-group form-md-line-input input-material">
                    <input type="text" name="fullName" class="form-control" id="form_control_1" value="{{ old('fullName') }}">
                    <label for="form_control_1"></label>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        {{-- 單位 --}}
        <div class="row">
            <div class="col-md-2 table_title">
                <span >單位 : </span>
            </div>
            <div class="col-md-4">
                <div class="form-group form-md-line-input">
                    <select class="form-control" id="unit" name="unit">
                        <option value="0" {{ old('unit')== 0 ? 'selected' : '' }}> 請選擇 (需指定後才能進行採購進貨操作)</option>
                        @foreach($material_units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit')== $unit->id ? 'selected' : '' }}> {{$unit->name}}</option>
                        @endforeach
                    </select>
                    <label for="unit" style="color:#248ff1;"></label>
                </div>
            </div>

        </div>

        <div class="col-md-12">
            <div class="col-md-2 table_title">
                <span ></span>
            </div>
            <div class="col-md-4">
                <div class="form-group form-md-line-input form-md-floating-label">
                    <input type="text" name="cost" class="form-control" id="form_control_2" value="{{ old('cost') }}">
                    <label for="form_control_2">預設每單位<span style="color:red">成本</span> (請輸入數字)</label>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-md-line-input form-md-floating-label">
                    <input type="text" name="price" class="form-control" id="form_control_3" value="{{ old('price') }}">
                    <label for="form_control_3">預設每單位<span style="color:red">售價</span> (請輸入數字)</label>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-2 table_title">
                <span >計價單位 : </span>
            </div>
            <div class="col-md-4">
                <div class="form-group form-md-line-input">
                    <select class="form-control" id="cal_unit" name="cal_unit">
                        <option value="0" {{ old('unit')== 0 ? 'selected' : '' }}> 未指定</option>
                        @foreach($material_units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit')== $unit->id ? 'selected' : '' }}> {{$unit->name}}</option>
                        @endforeach
                    </select>
                    <label for="cal_unit" style="color:#248ff1;"></label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-md-line-input form-md-floating-label">
                    <input type="text" name="cal_price" class="form-control" id="cal_price" value="{{ old('cal_price') }}">
                    <label for="cal_price">計價價格 (請輸入數字)</label>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-2 table_title">
                <span ></span>
            </div>
            <div class="col-md-4">
                <div class="form-group form-md-line-input form-md-floating-label">
                    <input type="text" name="size" class="form-control" id="form_control_4" value="{{ old('size') }}">
                    <label for="form_control_4">尺寸</label>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-md-line-input form-md-floating-label">
                    <input type="text" name="color" class="form-control" id="form_control_5" value="{{ old('color') }}">
                    <label for="form_control_5">顏色</label>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="col-md-2 table_title">
                <span ></span>
            </div>
            <div class="col-md-4">
                <div class="form-group form-md-line-input form-md-floating-label">
                    <input type="text" name="buy" class="form-control" id="form_control_6" value="{{ old('buy') }}">
                    <label for="form_control_6">預設採購量 (請輸入數字)</label>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group form-md-line-input form-md-floating-label input-material">
                    <input type="text" name="safe" class="form-control" id="form_control_7" value="{{ old('safe') }}">
                    <label for="form_control_7">安全量 (請輸入數字)</label>
                    <span class="help-block"></span>
                </div>
            </div>
        </div>


        <div class="col-md-12">
            <div class="col-md-2 table_title">
                <span >倉儲位置 : </span>
            </div>

            <div class="col-md-8">
                <div class="info-box hover-zoom-effect" >
                    <div class="icon bg-cyan">
                        <a href='javascript: openSelectWarehouse();'>
                            <i class="glyphicon glyphicon-th-large" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="content">
                        <div class="text">
                            <a href="javascript:delete_warehouse();">
                                <i class="glyphicon glyphicon-remove" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="number col-blue-grey">
                            <a href='javascript: openSelectWarehouse();'>
                                <div class="title col-orange" id="warehouse_show_1">
                                        (需指定後才能進行庫存操作)
                                </div>
                                <div><span id="warehouse_show_2"> 請點我選擇</span>
                                    @if(false)
                                        <span style="color:red;"> (關閉)</span>
                                    @endif
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="warehouse_id" id="warehouse_id">

        <div class="col-md-12">
            <div class="col-md-2 table_title">
                <span ></span>
            </div>
            <div class="col-md-8">
                <div class="form-group form-md-line-input">
                    <textarea class="form-control" rows="3" name="memo" id="memo">{{ old('memo') }}</textarea>
                    <label for="memo" style="color:#248ff1;font-size: 16px;">備註</label>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group form-md-line-input">
                <div class="col-md-2 table_title">
                    <span >啟用狀態:</span>
                </div>
                <div class="col-md-8">
                    <div class="md-radio-inline" style="margin-top:25px;">
                        <div class="md-radio has-info">
                            <input type="radio" id="radio1" name="status" class="md-radiobtn" value="1"
                            @if(old('status') == 1 || old('status') == null)
                                checked
                            @endif
                            >
                            <label for="radio1">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> 啟用 </label>
                        </div>
                        <div class="md-radio has-error">
                            <input type="radio" id="radio2" name="status" class="md-radiobtn" value="2" {{ old('status') == 2 ? 'checked' : '' }}>
                            <label for="radio2">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> 關閉 </label>
                        </div>
                    </div>
                </div>
            </div>
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
            <hr>
            <div class="form-actions noborder">
                <button type="submit" class="btn"><i class="fa fa-check"></i> 存 檔</button>
                <a href="{{ route('materials.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
            </div>
        </div>
        <input type="hidden" name="fullCode" id="fullCode_input">
    </form>

@endsection


@section('script')
<script>

if($('#code_2').val() == '' || $('#code_2').val() == 'undefiend' || $('#material_category').val() == ''){
    $('#fullCode').html('資料尚未完整');
}

function showFullCode() {

    if($('#code_2').val() == '' || $('#code_2').val() == 'undefiend' || $('#material_category').val() == ''){
        $('#fullCode').html('資料尚未完整');
    } else {
        var material_category = $('#material_category').val();
        var code_1 = $('#code_1').val();
        var code_2 = $('#code_2').val();
        var code_3 = $('#code_3').val();
        var dash_1 = '';

        if(code_3 == ''){
            dash_1 = '';
        } else {
            dash_1 = '-';
        }

        var str = material_category + code_1 + '-' + code_2 + dash_1 + code_3;
        $('#fullCode').html(str);
        $('#fullCode_input').val(str);


    }
}

function openSelectWarehouse() {
    $.magnificPopup.open({
        showCloseBtn : false,
        enableEscapeKey : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{{route('selectWarehouse')}}"}
    });
}

function setWarehouse(id,fullName,code,category){
    $.magnificPopup.close();
    var str = category+'                 <span class="col-blue-grey">'+fullName+'</span>';
    $('#warehouse_show_1').html(str);
    $('#warehouse_show_2').html(code);
    $('#warehouse_id').val(id);
}
function delete_warehouse(){
    $('#warehouse_show_1').html('(需指定後才能進行庫存操作)');
    $('#warehouse_show_2').html('請點我選擇');
    $('#warehouse_id').val('');
}

</script>
@endsection
