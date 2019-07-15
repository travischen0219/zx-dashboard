<script type="text/x-template" id="material-view">
    <div class="card card-default">
        <div class="card-body">
            <h4>
                物料清單
            </h4>

            <table id="" class="table">
                <thead>
                    <tr class="">
                        <th width="400">物料</th>
                        <th style="white-space: nowrap">
                            數量
                        </th>
                        <th style="white-space: nowrap">單位成本 / 小計</th>
                        <th style="white-space: nowrap">單位售價 / 小計</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, idx) in materials">
                        <td title="物料">
                            @{{ item.code + ' ' + item.name }}
                        </td>
                        <td title="數量">
                            倉庫：
                                @{{ item.amount }}
                                @{{ units[item.unit].name }}

                            <template v-if="item.cal == 1">
                                <div class="mt-1">
                                    計價：
                                    @{{ item.cal_amount }}
                                    @{{ units[item.cal_unit].name }}
                                </div>
                                <div class="mt-1">
                                    採購：
                                    @{{ item.buy_amount }}
                                    @{{ units[item.cal_unit].name }}
                                </div>
                            </template>
                        </td>
                        <td title="單位成本">
                            倉庫：
                            $@{{ item.cost }}
                            / $@{{ item.amount * item.cost | number_format }}

                            <template v-if="item.cal == 1">
                                <div class="mt-1">
                                    計價：
                                    $@{{ item.cal_price }}
                                    / $@{{ item.cal_amount * item.cal_price | number_format }}
                                </div>
                            </template>
                        </td>
                        <td title="單位售價">
                            $@{{ item.price }}
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
                &nbsp;&nbsp;&nbsp;&nbsp;
                售價總計：$@{{ total_price | number_format }}
            </div>
        </div>
    </div>
</script>

<script>
Vue.component('material-view', {
    template: '#material-view',

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

    },

    mounted: function () {

    }
});
</script>
