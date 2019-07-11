<script type="text/x-template" id="material-table">
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>
                模組物料清單
                <button type="button" @click="addRow" class="btn btn-primary btn-add">
                    <i class="fa fa-plus"></i> 新增物料
                </button>
            </h4>
            <hr>

            <table id="" class="table">
                <thead>
                    <tr>
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

                        <th style="white-space: nowrap">單位</th>
                        <th style="white-space: nowrap">單位成本</th>
                        <th style="white-space: nowrap">成本小計</th>
                        <th style="white-space: nowrap">單位售價</th>
                        <th style="white-space: nowrap">售價小計</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, idx) in rows">
                        <td title="操作">
                            <button type="button" @click="deleteRow(idx)"
                                class="btn red">
                                <i class="fa fa-remove"></i>
                            </button>
                            <input type="hidden" name="materialCalUnit[]" v-model="item.cal_unit" />
                            <input type="hidden" name="materialCalPrice[]" v-model="item.cal_price" />
                        </td>
                        <td title="物料">
                            <input type="hidden" name="material[]" v-model="item.id">
                            <button type="button"
                                @click="listMaterial(idx);"
                                class="btn btn-default btn-block">
                                @{{ item.id === 0 ? '請選擇物料' : item.code + ' ' + item.name }}
                            </button>
                        </td>
                        <td title="數量">
                            <input type="text"
                                class="form-control"
                                v-model="item.amount"
                                name="materialAmount[]"
                                placeholder="請輸入數字"
                                style="width: 100px;">
                        </td>
                        <td title="單位" style="vertical-align: middle;">
                            @{{ item.unit ? units[item.unit].name : '' }}
                            <input type="hidden" name="materialUnit[]" v-model="item.unit" />
                        </td>
                        <td title="單位成本">
                            <input type="text"
                                class="form-control"
                                v-model="item.cost"
                                name="materialCost[]"
                                placeholder="請輸入數字"
                                style="width: 100px;">
                        </td>
                        <td title="成本小計" style="vertical-align: middle;">
                            $@{{ item.amount * item.cost | number_format }}
                        </td>
                        <td title="單位售價">
                            <input type="text"
                                class="form-control"
                                v-model="item.price"
                                name="materialPrice[]"
                                placeholder="請輸入數字"
                                style="width: 100px;">
                        </td>
                        <td title="售價小計" style="vertical-align: middle;">
                            $@{{ item.amount * item.price | number_format }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <div class="text-right">
                共有 @{{ rows.length }} 種物料
                &nbsp;&nbsp;&nbsp;&nbsp;
                成本總計：$@{{ total_cost | number_format }}
                <input type="hidden" name="total_cost" v-model="total_cost">
                &nbsp;&nbsp;&nbsp;&nbsp;
                售價總計：$@{{ total_price | number_format }}
                <input type="hidden" name="total_price" v-model="total_price">
            </div>
        </div>
    </div>
</script>

<script>
Vue.component('material-table', {
    template: '#material-table',

    data: function () {
        return {
            row: { id: 0, name: '', amount: 0, cost: 0, price: 0 }
        }
    },

    props: {
        units: Object,
        rows: Array
    },

    computed: {
        total_cost: function() {
            var total_cost = 0
            this.rows.forEach(element => {
                total_cost += parseFloat(element.cost) * parseFloat(element.amount)
            })

            return total_cost
        },
        total_price: function() {
            var total_price = 0
            this.rows.forEach(element => {
                total_price += parseFloat(element.price) * parseFloat(element.amount)
            })

            return total_price
        }
    },

    methods: {
        addRow: function() {
            this.rows.push(Object.assign({}, this.row))
            this.$forceUpdate();
        },
        deleteRow: function(idx) {
            this.rows.splice(idx, 1);
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
        batchAmountApply() {
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

            app.rows.forEach(element => {
                element.amount *= parseFloat($("#batchAmount").val())
                element.amount = Math.round(element.amount * 100) / 100
            })

            $("#batchAmount").val('');
            $("#batchEdit").hide();
        }
    },

    mounted: function () {
        if (this.rows.length == 0) this.addRow()
    }
});

// 選取套用物料
function applyMaterial(str, idx) {
    var material = JSON.parse(str)
    // console.log(material)

    material = {
        id: material.id,
        category: material.material_categories_code,
        code: material.fullCode,
        name: material.fullName,
        amount: 0,
        unit: material.unit,
        cost: material.cost ? parseFloat(material.cost) : 0,
        price: material.price ? parseFloat(material.price) : 0,
        cal: material.cal ? parseInt(material.cal) : 0,
        cal_unit: material.cal_unit,
        cal_price: material.cal_price ? parseFloat(material.cal_price) : 0
    }

    app.$set(app.rows, idx, material)
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

    app.rows.forEach(function(element, index) {
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
