{{-- 物料表格 --}}
@include('vue.material_table')
@include('vue.material_view')
@include('vue.pay_table')

<script>
$(function () {
    $('.steps li').not('.steps > li:last-child').on('click', function(e) {
        e.stopPropagation()
        $('.steps li').not(this).not('.steps > li:last-child').removeClass('btn-primary').addClass('btn-secondary')
        $(this).removeClass('btn-secondary').addClass('btn-primary')

        $('#status').val($(this).data('status'))
    })

    $('#app').unbind('submit')

    $('#app').submit(function () {
        // 必填欄位檢查
        var error_message = ''
        if ($('#lot_id').val() == '') error_message += '<div>批號必須選擇</div>'
        if ($('#supplier_id').val() == '') error_message += '<div>供應商必須選擇</div>'
        if ($('#buy_date').val() == '') error_message += '<div>採購日期必須選擇</div>'

        if (error_message != '') {
            swalOption.type = "error"
            swalOption.title = '存檔失敗';
            swalOption.html = error_message;
            swal.fire(swalOption);
            return false;
        }

        // 入庫檢查
        const old_status = {{ $in->status }}
        const new_status = $('#status').val()

        if (old_status != 40 && new_status == 40) {
            stock_change_html = '<table class="table table-bordered">'

            app.materials.forEach(function (element) {
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
        } else {
            return true
        }

        return false
    })
})

var app = new Vue({
    el: '#app',
    data: {
        units: {!! $units !!},
        materials: {!! $materials !!},
        invoice_types: {!! $invoice_types !!},
        total_cost: {!! $total_cost !!},
        pays: {!! $pays !!}
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

function listManufacturers() {
    $.magnificPopup.open({
        showCloseBtn : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        items: {
            src: "/selector/manufacturer/1",
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

function applyManufacturer(str) {
    var manufacturer = JSON.parse(str)

    $('#manufacturer_id').val(manufacturer.id)
    $('#btn_manufacturer_id').html(manufacturer.code + ' ' + manufacturer.fullName)
}
</script>
