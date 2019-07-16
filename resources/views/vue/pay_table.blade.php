<script type="text/x-template" id="pay-table">
    <div class="card card-default mt-3">
        <div class="card-body">
            <h4>
                付款紀錄
                <button type="button" @click="addRow" class="btn btn-primary btn-add ml-2">
                    <i class="fa fa-plus"></i> 新增付款
                </button>
            </h4>

            <table id="" class="table">
                <thead>
                    <tr class="">
                        <th width="1" style="white-space: nowrap">操作</th>
                        <th>付款日期</th>
                        <th>金額</th>
                        <th>發票 / 收據</th>
                        <th colspan="2">註解</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, idx) in pays">
                        <td title="操作">
                            <button type="button" @click="deleteRow(idx)"
                                class="btn btn-danger">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                        <td title="付款日期">
                            <input type="text"
                                name="pay_date[]"
                                v-model="item.pay_date"
                                class="form-control datepicker"
                                readonly="readonly"
                                placeholder="請輸入付款日期" />
                        </td>
                        <td title="金額">
                            <input type="text"
                                name="pay_money[]"
                                v-model="item.pay_money"
                                class="form-control"
                                placeholder="請輸入金額" />
                        </td>
                        <td title="發票 / 收據">
                            <select class="form-control w-auto d-inline-block" name="pay_invoice_type[]" v-model="item.pay_invoice_type">
                                <option v-for="(invoice_type, key) in invoice_types" :value="key">
                                    @{{ invoice_type }}
                                </option>
                            </select>
                            <input type="text" v-model="item.pay_invoice_no" name="pay_invoice_no[]"
                                class="form-control w-auto d-inline-block" placeholder="請輸入發票或收據號碼" />
                        </td>
                        <td title="註解" colspan="2">
                            <input type="text" v-model="item.pay_memo" name="pay_memo[]"
                                class="form-control w-auto d-inline-block" placeholder="請輸入註解" />
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <div class="text-right">
                <div class="float-right text-left">
                <span class="text-white">＋</span> 應付：$@{{ total_cost | number_format }}
                &nbsp;&nbsp;
                － 實付：$@{{ total_pay | number_format }}
                &nbsp;&nbsp;
                ＝ 剩餘：$@{{ (total_cost - total_pay) | number_format }}
                </div>
            </div>
        </div>
    </div>
</script>

<script>
Vue.component('pay-table', {
    template: '#pay-table',

    data: function () {
        return {
            new_row: {
                pay_date: '{{ date("Y/m/d") }}',
                pay_money: 0,
                pay_invoice_type: 'C',
                pay_invoice_no: '',
                pay_memo: ''
            }
        }
    },

    props: {
        pays: Array,
        invoice_types: Object,
        total_cost: Number
    },

    computed: {
        total_pay: function() {
            var total_pay = 0
            this.pays.forEach(element => {
                total_pay += parseFloat(element.pay_money)
            })

            // this.$emit('update:total_pay', total_pay);

            return total_pay
        },

    },

    methods: {
        addRow() {
            this.pays.push(Object.assign({}, this.new_row))
            this.$forceUpdate()

            let _this = this

            this.$nextTick(function() {
                $('.datepicker:last-child').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    regional: 'zh-TW',
                    onClose: function(selectedDate, datePicker) {
                        _this.pays[_this.pays.length - 1].pay_date = $(this).val()
                    }
                })
            })
        },

        deleteRow(idx) {
            this.pays.splice(idx, 1);
        },

    },

    mounted: function () {
        // this.addRow()
    }
});
</script>
