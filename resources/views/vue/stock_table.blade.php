<script type="text/x-template" id="stock-table">
    <div class="card card-default">
        <div class="card-body">
            <h4>
                物料清單
                <button type="button" @click="listMaterial(stocks.length)" class="btn btn-primary ml-2">
                    <i class="fa fa-plus"></i> 新增物料
                </button>
                <button type="button" @click="listMaterialModule" class="btn btn-primary">
                    <i class="fa fa-plus"></i> 新增物料模組
                </button>
            </h4>

            <table id="" class="table">
                <thead>
                    <tr class="">
                        <th width="1" style="white-space: nowrap">操作</th>
                        <th width="400">物料</th>
                        <th style="white-space: nowrap">
                            數量
                            <a href="javascript: batchEditAmount();">
                                <small>批量修改</small>
                            </a>
                            <div id="batchEdit" style="margin-top: 2px; display: none;">
                                <input type="text" name="batchAmount" id="batchAmount" size="5" style="width: 50px;">
                                <button type="button" @click="batchAmountApply">x 倍數</button>
                            </div>
                        </th>
                        <th style="white-space: nowrap">庫存</th>
                        <th style="white-space: nowrap">備註</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, idx) in stocks">
                        <td title="操作">
                            <button type="button" @click="deleteRow(idx)"
                                class="btn btn-danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                        <td title="物料">
                            <input type="hidden" name="material_id[]" v-model="item.id">
                            <button type="button"
                                @click="listMaterial(idx);"
                                class="btn btn-primary btn-block">
                                @{{ item.id === 0 ? '請選擇物料' : item.code + ' ' + item.name }}
                            </button>
                        </td>
                        <td title="數量">
                            數量：<input type="text"
                                class="form-control"
                                v-model="item.amount"
                                name="material_amount[]"
                                placeholder="請輸入數字"
                                style="width: 100px;" />
                            @{{ units[item.unit].name }}
                        </td>
                        <td title="庫存" class="align-middle">
                            @{{ item.stock }}@{{ units[item.unit].name }}
                        </td>
                        <td title="備註">
                            備註：<input type="text"
                                class="form-control"
                                v-model="item.memo"
                                name="material_memo[]"
                                placeholder="請輸入備註"
                                style="width: 200px;" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</script>

<script>
Vue.component('stock-table', {
    template: '#stock-table',

    data: function () {
        return {
            row: {}
        }
    },

    props: {
        units: Object,
        stocks: Array
    },

    methods: {
        deleteRow: function(idx) {
            this.stocks.splice(idx, 1);
        },
        listMaterial(idx) {
            $.magnificPopup.open({
                showCloseBtn : false,
                closeOnBgClick: true,
                fixedContentPos: false,
                items: {
                    src: "/selector/material/" + idx,
                    type: "iframe"
                }
            })
        },
        listMaterialModule() {
            $.magnificPopup.open({
                showCloseBtn : false,
                closeOnBgClick: true,
                fixedContentPos: false,
                items: {
                    src: "/selector/material_module",
                    type: "iframe"
                }
            })
        },
        batchAmountApply() {
            // 數量 x 倍數
            if (isNaN($("#batchAmount").val())) {
                swal({
                    title: "倍數請輸入數字",
                    type: "warning",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: '確定',
                    closeOnConfirm: true
                }, function () {
                    $("#batchAmount").val('');
                });

                return false;
            }

            app.stocks.forEach(element => {
                element.amount *= parseFloat($("#batchAmount").val())
                element.amount = Math.round(element.amount * 100) / 100
            })

            $("#batchAmount").val('');
            $("#batchEdit").hide();
        }
    }
});

// 套用物料
function applyMaterial(str, idx) {
    // 一律新增，無法修改
    idx = app.stocks.length

    var material = JSON.parse(str)
    var duplicate = false

    material = {
        id: material.id,
        category: material.material_categories_code,
        code: material.fullCode,
        name: material.fullName,
        amount: 0,
        stock: material.stock,
        cal_amount: 0,
        buy_amount: 0,
        unit: material.unit,
        cost: material.cost ? parseFloat(material.cost) : 0,
        price: material.price ? parseFloat(material.price) : 0,
        cal: material.cal ? parseInt(material.cal) : 0,
        cal_unit: material.cal_unit,
        cal_price: material.cal_price ? parseFloat(material.cal_price) : 0
    }

    // 檢查是否重複
    app.stocks.forEach(function(row, i) {
        if (material.id == row.id && i != idx) {
            duplicate = true
        }
    })

    if (duplicate) {
        swalOption.type = "error"
        swalOption.title = '物料已經存在'
        swal.fire(swalOption)
    } else {
        app.$set(app.stocks, idx, material)
    }
}
// function applyMaterial(str, idx) {
//     var material = JSON.parse(str)
//     var duplicate = false

//     material = {
//         id: material.id,
//         category: material.material_categories_code,
//         code: material.fullCode,
//         name: material.fullName,
//         amount: 0,
//         stock: material.stock,
//         unit: material.unit
//     }

//     // 檢查是否重複
//     app.stocks.forEach(function(row, i) {
//         if (material.id == row.id && i != idx) {
//             duplicate = true
//         }
//     })

//     if (duplicate) {
//         swalOption.type = "error"
//         swalOption.title = '物料已經存在'
//         swal.fire(swalOption)
//     } else {
//         app.$set(app.stocks, idx, material)
//     }
// }

// 套用物料模組
function applyMaterialModule(str) {
    var appendMaterials = JSON.parse(str)
    var duplicate = false
    var duplicateMaterial = {}

    appendMaterials.forEach(function(material, idx) {
        // 檢查是否重複
        app.stocks.forEach(function(row, i) {
            if (material.id == row.id) {
                duplicate = true
                duplicateMaterial = material
            }
        })
    })

    if (duplicate) {
        swalOption.type = "error"
        swalOption.title = `物料重複`
        swalOption.text = `模組中 [ ${duplicateMaterial.code} ${duplicateMaterial.name} ] 已經存在，請先刪除重複的物料或重新選取物料模組`
        swal.fire(swalOption)
    } else {
        app.stocks = app.stocks.concat(appendMaterials)
    }
}
// function applyMaterialModule(str) {
//     var appendMaterials = JSON.parse(str)
//     var duplicate = false
//     var duplicateMaterial = {}

//     appendMaterials.forEach(function(material, idx) {
//         material.amount = 0

//         // 檢查是否重複
//         app.stocks.forEach(function(row, i) {
//             if (material.id == row.id) {
//                 duplicate = true
//                 duplicateMaterial = material
//             }
//         })
//     })

//     if (duplicate) {
//         swalOption.type = "error"
//         swalOption.title = `物料重複`
//         swalOption.text = `模組中 [ ${duplicateMaterial.code} ${duplicateMaterial.name} ] 已經存在，請先刪除重複的物料或重新選取物料模組`
//         swal.fire(swalOption)
//     } else {
//         app.stocks = app.stocks.concat(appendMaterials)
//     }

// }

// 顯示 / 隱藏 批量修改
function batchEditAmount() {
    $("#batchEdit").fadeToggle('fast', function() {
        $("#batchAmount").focus()
    })
}
</script>
