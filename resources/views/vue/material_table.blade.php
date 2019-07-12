<script type="text/x-template" id="material-table">
    <div class="card card-default">
        <div class="card-body">
            <h4>
                物料清單
                <button type="button" @click="listMaterial(materials.length)" class="btn btn-primary ml-2">
                    <i class="fa fa-plus"></i> 新增物料
                </button>
                <button type="button" v-if="module" @click="listMaterialModule" class="btn btn-primary">
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
                                <small>入庫批量修改</small>
                            </a>
                            <div id="batchEdit" style="margin-top: 2px; display: none;">
                                <input type="text" name="batchAmount" id="batchAmount" size="5" style="width: 50px;">
                                <button type="button" @click="batchAmountApply">x 倍數</button>
                            </div>
                        </th>
                        <th style="white-space: nowrap">單位成本 / 小計</th>
                        <th style="white-space: nowrap">單位售價 / 小計</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, idx) in materials">
                        <td title="操作">
                            <button type="button" @click="deleteRow(idx)"
                                class="btn btn-danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                        <td title="物料">
                            <input type="hidden" name="material[]" v-model="item.id">
                            <button type="button"
                                @click="listMaterial(idx);"
                                class="btn btn-primary btn-block">
                                @{{ item.id === 0 ? '請選擇物料' : item.code + ' ' + item.name }}
                            </button>
                        </td>
                        <td title="數量">
                            入庫：<input type="text"
                                class="form-control"
                                v-model="item.amount"
                                name="material_amount[]"
                                placeholder="請輸入數字"
                                style="width: 100px;" />
                                @{{ units[item.unit].name }}

                            <template v-if="item.cal == 1">
                                <div class="mt-1">
                                    計價：<input type="text"
                                        class="form-control"
                                        v-model="item.cal_amount"
                                        name="material_cal_amount[]"
                                        placeholder="請輸入數字"
                                        style="width: 100px;" />
                                    @{{ units[item.cal_unit].name }}
                                </div>
                                <div class="mt-1">
                                    採購：<input type="text"
                                        class="form-control"
                                        v-model="item.buy_amount"
                                        name="material_buy_amount[]"
                                        placeholder="請輸入數字"
                                        style="width: 100px;" />
                                    @{{ units[item.cal_unit].name }}
                                </div>
                            </template>
                        </td>
                        <td title="單位成本">
                            入庫：<input type="text"
                                class="form-control"
                                v-model="item.cost"
                                name="material_cost[]"
                                placeholder="請輸入數字"
                                style="width: 100px;">
                            / $@{{ item.amount * item.cost | number_format }}

                            <template v-if="item.cal == 1">
                                <div class="mt-1">
                                    計價：<input type="text"
                                        class="form-control"
                                        v-model="item.cal_price"
                                        name="material_cal_price[]"
                                        placeholder="請輸入數字"
                                        style="width: 100px;">
                                    / $@{{ item.cal_amount * item.cal_price | number_format }}
                                </div>
                            </template>
                        </td>
                        <td title="單位售價">
                            <input type="text"
                                class="form-control"
                                v-model="item.price"
                                name="material_price[]"
                                placeholder="請輸入數字"
                                style="width: 100px;">
                            / $@{{ item.amount * item.price | number_format }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <div class="text-right">
                共有 @{{ materials.length }} 種物料
                &nbsp;&nbsp;&nbsp;&nbsp;
                成本總計：$@{{ total_cost | number_format }}
                <input type="hidden" name="material_total_cost" v-model="total_cost">
                &nbsp;&nbsp;&nbsp;&nbsp;
                售價總計：$@{{ total_price | number_format }}
                <input type="hidden" name="material_total_price" v-model="total_price">
            </div>
        </div>
    </div>
</script>

<script>
Vue.component('material-table', {
    template: '#material-table',

    data: function () {
        return {
            row: {}
        }
    },

    props: {
        units: Object,
        materials: Array,
        module: true
    },

    computed: {
        total_cost: function() {
            var total_cost = 0
            this.materials.forEach(element => {
                total_cost += parseFloat(element.cost) * parseFloat(element.amount)
            })

            return total_cost
        },
        total_price: function() {
            var total_price = 0
            this.materials.forEach(element => {
                total_price += parseFloat(element.price) * parseFloat(element.amount)
            })

            return total_price
        }
    },

    methods: {
        deleteRow: function(idx) {
            this.materials.splice(idx, 1);
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

            app.materials.forEach(element => {
                element.amount *= parseFloat($("#batchAmount").val())
                element.amount = Math.round(element.amount * 100) / 100
            })

            $("#batchAmount").val('');
            $("#batchEdit").hide();
        }
    },

    mounted: function () {

    }
});

// 套用物料
function applyMaterial(str, idx) {
    var material = JSON.parse(str)
    // console.log(material)
    var duplicate = false

    material = {
        id: material.id,
        category: material.material_categories_code,
        code: material.fullCode,
        name: material.fullName,
        amount: 0,
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
    app.materials.forEach(function(row, i) {
        if (material.id == row.id && i != idx) {
            duplicate = true
        }
    })

    if (duplicate) {
        swalOption.type = "error"
        swalOption.title = '物料已經存在'
        swal.fire(swalOption)
    } else {
        app.$set(app.materials, idx, material)
    }
}

// 套用物料模組
function applyMaterialModule(str) {
    var material_module = JSON.parse(str)
    console.log(material_module.material2)

    var duplicate = false
    material_module.material2.forEach(function(material, idx) {

        material = {
            id: material.id,
            category: material.material_categories_code,
            code: material.fullCode,
            name: material.fullName,
            amount: 0,
            cal_amount: 0,
            buy_amount: 0,
            unit: material.unit,
            cost: material.cost ? parseFloat(material.cost) : 0,
            price: material.price ? parseFloat(material.price) : 0,
            cal: material.cal ? parseInt(material.cal) : 0,
            cal_unit: material.cal_unit,
            cal_price: material.cal_price ? parseFloat(material.cal_price) : 0
        }
    })



    // 檢查是否重複
    app.materials.forEach(function(row, i) {
        if (material.id == row.id && i != idx) {
            duplicate = true
        }
    })

    if (duplicate) {
        swalOption.type = "error"
        swalOption.title = '物料已經存在'
        swal.fire(swalOption)
    } else {
        app.$set(app.materials, idx, material)
    }
}

// 顯示 / 隱藏 批量修改
function batchEditAmount() {
    $("#batchEdit").fadeToggle('fast', function() {
        $("#batchAmount").focus()
    })
}

function checkMaterials() {
    var existMaterial = []
    var sameMaterial = []

    app.materials.forEach(function(element, index) {
        if (parseInt(element.id) != 0) {
            if (existMaterial.includes(element.id)) {
                sameMaterial.push(element.name)
            } else {
                existMaterial.push(parseInt(element.id))
            }
        }
    })

    // 有重複物料
    if (sameMaterial.length > 0) {
        swalOption.title = '選擇的物料有重複'
        swalOption.text = sameMaterial.join('\n')
        swal(swalOption)

        return false
    } else {
        return true
    }
}
</script>
