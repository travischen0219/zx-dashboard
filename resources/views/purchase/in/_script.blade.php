{{-- 物料表格 --}}
@include('vue.in_material_table')

<script>
var app = new Vue({
    el: '#app',
    data: {
        units: {!! $units !!},
        rows: []
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
