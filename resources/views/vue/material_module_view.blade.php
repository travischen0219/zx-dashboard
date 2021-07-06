<script type="text/x-template" id="material-module-view">
    <div class="card card-default mb-3">
        <div class="card-body">
            <h4>
                物料模組清單
            </h4>

            <table id="" class="table">
                <thead>
                    <tr class="">
                        <th width="400">物料模組</th>
                        <th style="white-space: nowrap">數量</th>
                        <th style="white-space: nowrap">成本 / 小計</th>
                        <th style="white-space: nowrap">售價 / 小計</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, idx) in material_modules">
                        <td title="物料模組">
                            @{{ item.code + ' ' + item.name }}
                        </td>
                        <td title="數量">
                            @{{ item.amount }} @{{ item.unit }}
                        </td>
                        <td title="成本" class="align-middle">
                            $@{{ item.cost | number_format }}
                            / $@{{ item.amount * item.cost | number_format }}
                        </td>
                        <td title="售價">
                            $@{{ item.price | number_format }}
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
                &nbsp;&nbsp;&nbsp;&nbsp;
                售價總計 (應收)：$@{{ total_price | number_format }}
            </div>
        </div>
    </div>
</script>

<script>
Vue.component('material-module-view', {
    template: '#material-module-view',

    data: function () {
        return {
            row: {}
        }
    },

    props: {
        material_modules: Array
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

    mounted: function () {

    }
});
</script>
