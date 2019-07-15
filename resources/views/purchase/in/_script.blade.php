{{-- 物料表格 --}}
@include('vue.material_table')
@include('vue.material_view')
@include('vue.pay_table')

<script>
$(function () {
    $('.steps li').on('click', function(e) {
        e.stopPropagation()
        $('.steps li').not(this).removeClass('active')
        $(this).addClass('active')

        $('#status').val($(this).data('status'))
    })
})

var app = new Vue({
    el: '#app',
    data: {
        units: {!! $units !!},
        materials: {!! $materials !!},
        invoice_types: {!! $invoice_types !!},
        total_cost: 0,
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
