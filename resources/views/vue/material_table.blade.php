<script type="text/x-template" id="material-table">
    <div class="card card-default">
        <div class="card-body">
            <div class="float-left">
                <small>物料清單</small>
                <button type="button" @click="listMaterial(materials.length)" class="btn btn-primary btn-sm ml-2">
                    <i class="fa fa-plus"></i> 新增物料
                </button>
                <button type="button" v-if="module" @click="listMaterialModule" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> 新增物料模組
                </button>
            </div>

            <div class="float-right">
                <button type="button" v-if="update" @click="updatePrice" class="btn btn-warning btn-sm">
                    <i class="fas fa-redo"></i>
                    更新價錢
                </button>

                <!--button type="button" @click="selectIn" class="btn btn-primary btn-sm ml-1">
                    <i class="fas fa-archive"></i>
                    入庫
                </button-->

                <!--button type="button" @click="selectDelete" class="btn btn-danger btn-sm ml-1">
                    <i class="fas fa-trash"></i>
                    刪除
                </button-->
            </div>

            <div class="clearfix"></div>

            <table id="" class="table mt-2" style="font-size: .6rem;">
                <thead>
                    <tr class="">
                        <th width="1" style="white-space: nowrap">操作</th>
                        <th>物料</th>
                        <th style="white-space: nowrap">
                            數量
                            <a href="javascript: batchEditAmount();">
                                <small>倉庫批量修改</small>
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
                        <td title="操作" class="align-middle">
                            <button type="button" @click="deleteRow(idx)"
                                v-if="item.in != 1" class="btn btn-danger btn-sm">
                                <i class="fas fa-times"></i>
                            </button>

                            <!--input type="checkbox"
                                :id="`idx-` + item.id"
                                :value="idx"
                                v-if="item.in != 1"
                                v-model="idxs"-->
                        </td>
                        <td title="物料">
                            <input type="hidden" name="material[]" v-model="item.id">
                            <!--button v-if="item.in != 1" type="button" @click="oneIn(idx)"
                                class="btn btn-warning btn-sm">
                                入庫
                            </button-->
                            <button type="button"
                                style="font-size: 12px;"
                                class="btn btn-default btn-sm text-left">
                                @{{ item.id === 0 ? '請選擇物料' : item.code + ' ' + item.name }}
                            </button>
                            <label v-if="canIn && item.in != 1" class="text-primary w-auto">
                                <input type="checkbox"
                                    name="in[]"
                                    class="align-middle"
                                    :value="idx">
                                    入庫
                            </label>
                        </td>
                        <td title="數量">
                            數量：<input type="text"
                                class="form-control p-1"
                                v-model="item.amount"
                                name="material_amount[]"
                                placeholder="請輸入數字"
                                style="width: 55px; font-size: 12px; height: 24px;" />
                            / 庫存：@{{ item.stock }}@{{ units[item.unit].name }}

                            <template v-if="item.cal == 1">
                                <div class="mt-1">
                                    計價：<input type="text"
                                        class="form-control p-1"
                                        v-model="item.cal_amount"
                                        name="material_cal_amount[]"
                                        placeholder="請輸入數字"
                                        style="width: 55px;" />
                                    @{{ units[item.cal_unit].name }}
                                </div>
                                <div class="mt-1">
                                    採購：<input type="text"
                                        class="form-control p-1"
                                        v-model="item.buy_amount"
                                        name="material_buy_amount[]"
                                        placeholder="請輸入數字"
                                        style="width: 55px; font-size: 12px; height: 24px;" />
                                    @{{ units[item.cal_unit].name }}
                                </div>
                            </template>
                        </td>
                        <td title="單位成本">
                            成本：<input type="text"
                                class="form-control p-1"
                                v-model="item.cost"
                                name="material_cost[]"
                                placeholder="請輸入數字"
                                style="width: 55px; font-size: 12px; height: 24px;">
                            / $@{{ item.amount * item.cost | number_format }}

                            <template v-if="item.cal == 1">
                                <div class="mt-1">
                                    計價：<input type="text"
                                        class="form-control p-1"
                                        v-model="item.cal_price"
                                        name="material_cal_price[]"
                                        placeholder="請輸入數字"
                                        style="width: 55px; font-size: 12px; height: 24px;">
                                    / $@{{ item.buy_amount * item.cal_price | number_format }}
                                </div>
                            </template>
                        </td>
                        <td title="單位售價">
                            <input type="text"
                                class="form-control p-1"
                                v-model="item.price"
                                name="material_price[]"
                                placeholder="請輸入數字"
                                style="width: 55px; font-size: 12px; height: 24px;">
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
            row: {},
            idxs: []
        }
    },

    props: {
        dataId: Number,
        units: Object,
        materials: Array,
        module: true,
        update: false,
        canIn: false    // 是否可以入庫
    },

    computed: {
        total_cost: function() {
            var total_cost = 0
            this.materials.forEach(element => {
                if (element.cal == 1) {
                    total_cost += parseFloat(element.cal_price) * parseFloat(element.buy_amount)
                } else {
                    total_cost += parseFloat(element.cost) * parseFloat(element.amount)
                }
            })

            this.$emit('update:total_cost', total_cost);
            return total_cost
        },
        total_price: function() {
            var total_price = 0
            this.materials.forEach(element => {
                total_price += parseFloat(element.price) * parseFloat(element.amount)
            })

            this.$emit('update:total_price', total_price);

            return total_price
        }
    },

    methods: {
        deleteRow: function(idx) {
            if (confirm('確定刪除？')) {
                this.materials.splice(idx, 1);
            }
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
        },
        updatePrice() {
            const _this = this

            swal.fire({
                title: '所有成本及價錢將會更新成預設值',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: '確定更新',
                cancelButtonText: '取消',
                width: 600
            }).then((result) => {
                if (result.value) {
                    $.busyLoadFull("show", {
                        textPosition: "bottom",
                        textMargin: "20px",
                        background: "rgba(0, 0, 0, 0.70)",
                        text: '價錢更新中，請勿關閉或離開...'
                    })

                    const ids = this.materials.map(function(item, index, array) {
                        return item.id
                    })

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                    $.ajax({
                        type: 'POST',
                        data: { ids: ids },
                        url: '/settings/material/getall',
                        success: function(materials) {
                            materials.forEach(function(material, materialIndex, materialArray) {
                                _this.materials.forEach(function(item, index, array) {
                                    if (material.id == item.id) {
                                        // update price
                                        item.cost = material.cost
                                        item.price = material.price
                                        item.cal_price = material.cal_price
                                    }
                                })
                            })

                            swal.fire(
                                '價錢已更新',
                                '請記得存檔以更新成本及價錢',
                                'success'
                            )

                            $.busyLoadFull('hide')
                        }
                    })
                } else {
                    return false
                }
            })
        }
    },

    mounted: function () {
        console.log(this.dataId)
    }
});

// 套用物料
function applyMaterial(str, idx) {
    // 一律新增，無法修改
    idx = app.materials.length

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
    var appendMaterials = JSON.parse(str)
    var duplicate = false
    var duplicateMaterial = {}

    appendMaterials.forEach(function(material, idx) {
        // 檢查是否重複
        app.materials.forEach(function(row, i) {
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
        app.materials = app.materials.concat(appendMaterials)
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
