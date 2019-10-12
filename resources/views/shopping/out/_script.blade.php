{{-- 物料表格 --}}
@include('vue.material_module_table')
@include('vue.material_module_view')
@include('vue.pay_table')

<script>
$(function () {
    $('.steps li').on('click', function(e) {
        e.stopPropagation()
        $('.steps li').not(this).removeClass('btn-primary').addClass('btn-secondary')
        $(this).removeClass('btn-secondary').addClass('btn-primary')

        $('#status').val($(this).data('status'))
    })

    $('#app').unbind('submit')

    $('#app').submit(function () {
        // 必填欄位檢查
        var error_message = ''
        // if ($('#lot_id').val() == 0) error_message += '<div>批號必須選擇</div>'
        if ($('#customer_id').val() == 0) error_message += '<div>客戶必須選擇</div>'
        if ($('#created_date').val() == '') error_message += '<div>新增日期必須選擇</div>'

        // if ($('#sale_cost').val() != '') {
        //     if (isNaN($('#sale_cost').val()) || $('#sale_cost').val() <= 0) {
        //         error_message += '<div>管銷比率請輸入大於0的數字</div>'
        //     }
        // }

        if (app.material_modules.length <= 0) error_message += '<div>尚未選取任何物料模組</div>'

        var unsignedNumber = false
        app.material_modules.forEach(function(element) {
            if (isNaN(element.amount) || element.amount <= 0) {
                unsignedNumber = true
            }
        })
        if (unsignedNumber) error_message += '<div>數量請輸入大於0的數字</div>'

        if (error_message != '') {
            swalOption.type = "error"
            swalOption.title = '存檔失敗';
            swalOption.html = error_message;
            swal.fire(swalOption);
            return false;
        }

        // 出庫檢查
        const old_status = {{ $out->status }}
        const new_status = $('#status').val()

        if (old_status != 40 && new_status == 40) {
            // stock_change_html = '<table class="table table-bordered">'

            // app.materials.forEach(function (element) {
            //     const unit = app.units[element.unit]
            //     const old_stock = element.stock
            //     let new_stock = Number(element.stock) + Number(element.amount)
            //     new_stock = new_stock.toFixed(2)

            //     stock_change_html += `
            //         <tr>
            //             <td class="text-left">${element.code} ${element.name}</td>
            //             <td class="text-right">${old_stock}${unit.name} <span class="rotate-down">→</span> ${new_stock}${unit.name}</td>
            //         </tr>
            //     `
            // })

            // stock_change_html += `</table>`

            swal.fire({
                title: '庫存將發生改變',
                html: '',
                type: 'info',
                showCancelButton: true,
                confirmButtonText: '確定出庫',
                cancelButtonText: '取消',
                width: 600
            }).then((result) => {
                if (result.value) {
                    $.busyLoadFull("show", {
                        textPosition: "bottom",
                        textMargin: "20px",
                        background: "rgba(0, 0, 0, 0.70)",
                        text: '資料送出中，請勿關閉或離開...'
                    })

                    $('#app')[0].submit()
                } else {
                    return false
                }
            })
        } else {
            return true
        }

        return false
    })
})

var app = new Vue({
    el: '#app',
    data: {
        material_modules: {!! $material_modules !!},
        invoice_types: {!! $invoice_types !!},
        total_cost: {!! $total_cost !!},
        total_price: {!! $total_price !!},
        pays: {!! $pays !!},
        tax: {!! $tax !!},
    }
})

$('.datepicker').datepicker({
    changeMonth: true,
    changeYear: true,
    regional: 'zh-TW'
})

function listLots() {
    $.magnificPopup.open({
        showCloseBtn : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        items: {
            src: "/selector/lot/",
            type: "iframe"
        }
    })
}

function listCustomers() {
    $.magnificPopup.open({
        showCloseBtn : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        items: {
            src: "/selector/customer",
            type: "iframe"
        }
    })
}

function applyLot(str) {
    var lot = JSON.parse(str)

    $('#lot_id').val(lot.id)
    $('#btn_lot_id').html(lot.code + ' ' + lot.name)
}

function applyCustomer(str) {
    var customer = JSON.parse(str)

    $('#customer_id').val(customer.id)
    $('#btn_customer_id').html(customer.code + ' ' + customer.fullName)
}
</script>
