<script type="text/x-template" id="material-table">
    <div class="panel panel-default">
        <div class="panel-body">
            <h4>
                物料清單
                <button type="button" @click="addRow" class="btn btn-primary btn-add">
                    <i class="fa fa-plus"></i> 新增物料
                </button>
            </h4>
            <hr>

            <table id="materialTable" class="table">
                <thead>
                    <tr>
                        <th width="1" style="white-space: nowrap">操作</th>
                        <th>物料</th>
                        <th width="1" style="white-space: nowrap">
                            數量
                            <a href="javascript: batchEditAmount();">
                                <small>批量修改</small>
                            </a>
                            <div id="batchEdit" style="margin-top: 2px; display: none;">
                                <input type="text" name="batchAmount" id="batchAmount" size="5" style="width: 50px;">
                                <button type="button" @click="batchAmountApply">x 倍數</button>
                            </div>
                        </th>
                        <th width="1" style="white-space: nowrap">單位</th>
                        <th width="1" style="white-space: nowrap">單位成本</th>
                        <th width="1" style="white-space: nowrap">成本小計</th>
                        <th width="1" style="white-space: nowrap">單位售價</th>
                        <th width="1" style="white-space: nowrap">售價小計</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, idx) in rows">
                        <td title="操作">
                            <button type="button" @click="deleteRow(idx)"
                                class="btn red">
                                <i class="fa fa-remove"></i>
                            </button>
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
                                placeholder="請輸入數字">
                        </td>
                        <td title="單位">@{{ item.unit ? units[item.unit].name : '' }}</td>
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
                element.amount = element.amount.toFixed(2)
            })

            $("#batchAmount").val('');
            $("#batchEdit").hide();
        }
    }
});

function applyMaterial(str, idx) {
    var material = JSON.parse(str)

    material = {
        id: material.id,
        code: material.fullCode,
        name: material.fullName,
        amount: 0,
        unit: material.unit,
        cost: material.cost ? parseFloat(material.cost) : 0,
        price: material.price ? parseFloat(material.price) : 0
    }

    app.$set(app.rows, idx, material)
}

function batchEditAmount() {
    $("#batchEdit").fadeToggle('fast');
}
</script>
