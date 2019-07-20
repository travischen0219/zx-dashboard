@include('vue.stock_table')

<script>
var app = new Vue({
    el: '#app',
    data: {
        stocks: [],
        units: {!! $units !!}
    }
})

$(function () {
    $('.datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
        regional: 'zh-TW'
    })

    $('#app').unbind('submit')

    $('#app').submit(function () {
        // 必填欄位檢查
        var error_message = ''
        if ($('#stock_date').val() == '') error_message += '<div>入庫日期必須選擇</div>'
        if (app.stocks.length <= 0) error_message += '<div>尚未選取任何物料</div>'

        if (error_message != '') {
            swalOption.type = "error"
            swalOption.title = '存檔失敗';
            swalOption.html = error_message;
            swal.fire(swalOption);
            return false;
        }

        // 入庫檢查
        stock_change_html = '<table class="table table-bordered">'

        app.stocks.forEach(function (element) {
            const unit = app.units[element.unit]
            const old_stock = element.stock
            let new_stock = Number(element.stock) + Number(element.amount)
            new_stock = new_stock.toFixed(2)

            stock_change_html += `
                <tr>
                    <td class="text-left">${element.code} ${element.name}</td>
                    <td class="text-right">${old_stock}${unit.name} → ${new_stock}${unit.name}</td>
                </tr>
            `
        })

        stock_change_html += `</table>`

        swal.fire({
            title: '庫存將發生改變',
            html: stock_change_html,
            type: 'info',
            showCancelButton: true,
            confirmButtonText: '確定入庫',
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

        return false
    })

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

function listSuppliers() {
    $.magnificPopup.open({
        showCloseBtn : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        items: {
            src: "/selector/supplier/1",
            type: "iframe"
        }
    })
}

function applyLot(str) {
    var lot = JSON.parse(str)

    $('#lot_id').val(lot.id)
    $('#btn_lot_id').html(lot.code + ' ' + lot.name)
}

function applySupplier(str) {
    var supplier = JSON.parse(str)

    $('#supplier_id').val(supplier.id)
    $('#btn_supplier_id').html(supplier.code + ' ' + supplier.fullName)
}
</script>
