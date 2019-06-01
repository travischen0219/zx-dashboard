@extends('layouts.app')

@section('title','新增物料模組')

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

    #materialTable td {
        line-height: 2.44;
    }
    #materialTable input[type=text] {
        width: 100px;
    }
    .btn-add {
        margin-left: 10px;
    }
    .mfp-iframe-holder .mfp-content {
        width: 85%;
        height: 85%;
        max-width: 100%;
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
    <h1 class="page-title"> 新增物料模組
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->

</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div id="app" class="row">

    <div class="col-md-12 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light">
            @include('includes.messages')

            <div class="portlet-body form">
                <form role="form" action="{{ route('material_module.store') }}" method="POST" id="material_module_from" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <div class="form-group">
                        編號 : <span style="color:#000">自動產生</span>
                    </div>

                    <div class="form-group form-md-line-input form-md-floating-label">
                        <input type="text" name="name" class="form-control" id="name" value="">
                        <label for="name" style="color: #248ff1;">名稱</label>
                        <span class="help-block"></span>
                    </div>

                    <div class="form-group form-md-line-input form-md-floating-label">
                        <textarea class="form-control" rows="3" name="memo" id="memo"></textarea>
                        <label for="memo" style="color:#248ff1;font-size: 16px;">產品說明</label>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4>
                                物料清單
                                <button type="button" @click="addRow" class="btn btn-primary btn-add">
                                    <i class="fa fa-plus"></i> 新增物料
                                </button>
                            </h4>
                            <hr>

                            {{-- 物料清單2 --}}
                            <table id="materialTable" class="table">
                                <thead>
                                    <tr>
                                        <th width="70" nowrap>操作</th>
                                        <th>物料</th>
                                        <th width="150" nowrap>數量</th>
                                        <th width="150" nowrap>單位</th>
                                        <th width="150" nowrap>單位成本</th>
                                        <th width="150" nowrap>成本小計</th>
                                        <th width="150" nowrap>單位售價</th>
                                        <th width="150" nowrap>售價小計</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(item, index) in materialRows">
                                        <td title="操作">
                                            <button type="button" @click="deleteRow(index)"
                                                class="btn red">
                                                <i class="fa fa-remove"></i>
                                            </button>
                                        </td>
                                        <td title="物料">
                                            <input type="hidden" name="material[]" v-model="item.id">
                                            <button type="button"
                                                @click="listMaterial(index);"
                                                class="btn btn-default btn-block">
                                                @{{ item.id === 0 ? '請選擇物料' : item.code + ' ' + item.name }}
                                            </button>
                                        </td>
                                        <td title="數量">
                                            <input type="text"
                                                class="form-control"
                                                v-model="item.amount"
                                                name="materialAmount[]"
                                                placeholder="請輸入數字">
                                        </td>
                                        <td title="單位">@{{ item.unit }}</td>
                                        <td title="單位成本">
                                            <input type="text"
                                                class="form-control"
                                                v-model="item.cost"
                                                name="materialCost[]"
                                                placeholder="請輸入數字">
                                        </td>
                                        <td title="成本小計">
                                            $@{{ item.amount * item.cost | number_format }}
                                        </td>
                                        <td title="單位售價">
                                            <input type="text"
                                                class="form-control"
                                                v-model="item.price"
                                                name="materialPrice[]"
                                                placeholder="請輸入數字">
                                        </td>
                                        <td title="售價小計">
                                            $@{{ item.amount * item.price | number_format }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr>

                            <div class="text-right">
                                共有 @{{ materialRows.length }} 種物料
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                成本總計：$@{{ total_cost | number_format }}
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                售價總計：$@{{ total_price | number_format }}
                            </div>
                        </div>
                    </div>

                    <div class="form-body">
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
                                                    <span class="fileinput-new" style=""> 選擇檔案(主圖) </span>
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
                                <a href="{{ route('material_module.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
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
var app = new Vue({
    el: '#app',
    data: {
        currnetIndex: 0,
        materialRow: { id: 0, name: '', amount: 0, cost: 0, price: 0 },
        materialRows: [ ]
    },
    computed: {
        total_cost: function() {
            var total_cost = 0
            this.materialRows.forEach(element => {
                total_cost += parseFloat(element.cost) * parseFloat(element.amount)
            })

            return total_cost
        },
        total_price: function() {
            var total_price = 0
            this.materialRows.forEach(element => {
                total_price += parseFloat(element.price) * parseFloat(element.amount)
            })

            return total_price
        }
    },
    methods: {
        addRow: function() {
            this.materialRows.push(Object.assign({}, this.materialRow))
        },
        deleteRow: function(index) {
            this.materialRows.splice(index, 1);
        },
        listMaterial(index) {
            this.currnetIndex = index

            $.magnificPopup.open({
                showCloseBtn : false,
                closeOnBgClick: true,
                fixedContentPos: false,
                items: {
                    src: "/selector/material",
                    type: "iframe"
                }
            })
        }
    },
    created: function() {
        this.addRow() // 新增一空列
    }
})

var swalOption = {
    title: "",
    text: "",
    type: "warning",
    showCancelButton: false,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: '確定',
    cancelButtonText: '取消',
    closeOnConfirm: true
};

$(function () {

});

function applyMaterial(str) {
    var material = JSON.parse(str);

    material = {
        id: material.id,
        code: material.fullCode,
        name: material.fullName,
        amount: 0,
        cost: material.cost ? parseFloat(material.cost) : 0,
        price: material.price ? parseFloat(material.price) : 0
    };

    app.$set(app.materialRows, app.currnetIndex, material);
    app.$forceUpdate();
}

function submit_btn(){
    if ($('#name').val() == '') {
        swalOption.title = '請輸入名稱';
        swal(swalOption);
        return false;
    }

    var existNaN = false;
    var materialSum = 0;

    var existMaterial = [];
    var sameMaterial = [];

    app.materialRows.forEach(function(element, index) {
        // 檢查非數字
        if(isNaN(element.amount) || isNaN(element.cost) || isNaN(element.price)) {
            existNaN = true;
        }

        // 檢查物料數量
        materialSum += element.id

        // 檢查重複物料
        if (existMaterial.includes(element.id)) {
            sameMaterial.push(element.name)
        } else {
            existMaterial.push(element.id);
        }
    });

    // 有非數字
    if(existNaN){
        swalOption.title = '數量、成本或售價必須為數字';
        swal(swalOption);
        return false;
    }

    // 物料數量
    if(materialSum == 0){
        swalOption.title = '未選擇任何物料';
        swal(swalOption);
        return false;
    }

    // 有重複物料
    if (sameMaterial.length > 0) {
        swalOption.title = '選擇的物料有重複';
        swalOption.text = sameMaterial.join('\n');
        swal(swalOption);

        return false;
    }

    // 驗證完成，保存
    $("#material_module_from").submit();
}
</script>
@endsection
