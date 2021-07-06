<script type="text/x-template" id="material-module-table">
    <div class="card card-default mb-3">
        <div class="card-body">
            <h4>
                物料模組清單
                <button type="button" @click="listMaterialModule(material_modules.length)" class="btn btn-primary">
                    <i class="fa fa-plus"></i> 新增物料模組
                </button>

                <button type="button" v-if="update" @click="updatePrice" class="btn btn-warning float-right">
                    <i class="fas fa-redo"></i>
                    更新價錢
                </button>
            </h4>

            <table id="" class="table">
                <thead>
                    <tr class="">
                        <th width="1" style="white-space: nowrap">操作</th>
                        <th width="400">物料模組</th>
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
                        <th style="white-space: nowrap">成本 / 小計</th>
                        <th style="white-space: nowrap">售價 / 小計</th>
                    </tr>
                </thead>

                <tbody class="sortable">
                    <tr v-for="(item, idx) in material_modules">
                        <td title="操作">
                            <button type="button" @click="deleteRow(idx)"
                                class="btn btn-danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                        <td title="物料模組">
                            <input type="hidden" name="material_module[]" v-model="item.id">
                            <input type="hidden" name="material_module_code[]" v-model="item.code">
                            <input type="hidden" name="material_module_name[]" v-model="item.name">
                            <input type="hidden" name="material_module_cost[]" v-model="item.cost">
                            <button type="button"
                                @click="listMaterialModule(idx);"
                                class="btn btn-primary">
                                @{{ item.id === 0 ? '請選擇物料模組' : item.code + ' ' + item.name }}
                            </button>

                            <a href="javascript: void(0)" @click="inputMemo(idx)" class="ml-1">
                                <i class="far fa-file-alt" :class="{'text-muted': item.memo == '' || item.memo == null}"></i>
                            </a>
                            <div v-show="item.show_memo == 1">
                                註解：<input type="text"
                                class="form-control mt-2"
                                v-model="item.memo"
                                name="material_module_memo[]"
                                placeholder="請輸入註解" />
                            </div>
                        </td>
                        <td title="數量">
                            數量：<input type="text"
                                class="form-control"
                                v-model="item.amount"
                                name="material_module_amount[]"
                                placeholder="請輸入數字"
                                style="width: 100px;" />
                            @{{ item.unit }}
                        </td>
                        <td title="成本" class="align-middle">
                            $@{{ item.cost | number_format }}
                            / $@{{ item.amount * item.cost | number_format }}
                        </td>
                        <td title="售價">
                            <input type="text"
                                class="form-control"
                                v-model="item.price"
                                name="material_module_price[]"
                                placeholder="請輸入數字"
                                style="width: 100px;">
                            / $@{{ item.amount * item.price | number_format }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <div class="text-right">
                共有 @{{ material_modules.length }} 種物料模組
                &nbsp;&nbsp;&nbsp;&nbsp;
                成本總計 (應付)：$@{{ total_cost | number_format }}
                <input type="hidden" name="material_module_total_cost" v-model="total_cost">
                &nbsp;&nbsp;&nbsp;&nbsp;
                售價總計 (應收)：$@{{ total_price | number_format }}
                <input type="hidden" name="material_module_total_price" v-model="total_price">
            </div>
        </div>
    </div>
</script>

<script>
Vue.component('material-module-table', {
    template: '#material-module-table',

    data: function () {
        return {
            row: {},
        }
    },

    props: {
        material_modules: Array,
        update: false,
        units: Object,
    },

    computed: {
        total_cost: function() {
            var total_cost = 0
            this.material_modules.forEach(element => {
                total_cost += parseFloat(element.cost) * parseFloat(element.amount)
            })

            this.$emit('update:total_cost', total_cost);

            return total_cost
        },
        total_price: function() {
            var total_price = 0
            this.material_modules.forEach(element => {
                total_price += parseFloat(element.price) * parseFloat(element.amount)
            })

            this.$emit('update:total_price', total_price);

            return total_price
        }
    },

    methods: {
        inputMemo(idx) {
            if (this.material_modules[idx].show_memo == 1) {
                this.$set(this.material_modules[idx], 'show_memo', 0)
            } else {
                this.$set(this.material_modules[idx], 'show_memo', 1)
            }
        },
        deleteRow: function(idx) {
            this.material_modules.splice(idx, 1);
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
        listMaterialModule(idx) {
            $.magnificPopup.open({
                showCloseBtn : false,
                closeOnBgClick: true,
                fixedContentPos: false,
                items: {
                    src: "/selector/material_module/" + idx,
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

            app.material_modules.forEach(element => {
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

                    const ids = this.material_modules.map(function(item, index, array) {
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
                        url: '/settings/material_module/getall',
                        success: function(material_modules) {
                            // console.log(material_modules)
                            // console.log(_this.material_modules)
                            material_modules.forEach(function(material_module, material_moduleIndex, material_moduleArray) {
                                _this.material_modules.forEach(function(item, index, array) {
                                    if (material_module.id == item.id) {
                                        // update price
                                        item.cost = material_module.total_cost
                                        item.price = material_module.price
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
        $('.sortable').sortable()
    }
});

// 套用物料
function applyMaterialModule(str, idx) {
    var materialModule = JSON.parse(str)
    var duplicate = false

    materialModule = {
        id: materialModule.id,
        code: materialModule.code,
        name: materialModule.name,
        amount: 0,
        cost: materialModule.total_cost ? parseFloat(materialModule.total_cost) : 0,
        price: materialModule.price ? parseFloat(materialModule.price) : 0,
    }

    // 檢查是否重複
    app.material_modules.forEach(function(row, i) {
        if (materialModule.id == row.id && i != idx) {
            duplicate = true
        }
    })

    if (duplicate) {
        swalOption.type = "error"
        swalOption.title = '物料模組已經存在'
        swal.fire(swalOption)
    } else {
        console.log(materialModule)
        app.$set(app.material_modules, idx, materialModule)
    }
}

// 顯示 / 隱藏 批量修改
function batchEditAmount() {
    $("#batchEdit").fadeToggle('fast', function() {
        $("#batchAmount").focus()
    })
}

function checkMaterialModules() {
    var existMaterialModule = []
    var sameMaterialModule = []

    app.material_modules.forEach(function(element, index) {
        if (parseInt(element.id) != 0) {
            if (existMaterialModule.includes(element.id)) {
                sameMaterialModule.push(element.name)
            } else {
                existMaterialModule.push(parseInt(element.id))
            }
        }
    })

    // 有重複物料
    if (sameMaterialModule.length > 0) {
        swalOption.title = '選擇的物料模組有重複'
        swalOption.text = sameMaterialModule.join('\n')
        swal(swalOption)

        return false
    } else {
        return true
    }
}
</script>
