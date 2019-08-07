<script>
$('#start_date, #end_date').datepicker({
    changeMonth: true,
    changeYear: true,
    regional: 'zh-TW'
})

function listCustomers() {
    $.magnificPopup.open({
        showCloseBtn : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        items: {
            src: "/selector/customer/",
            type: "iframe"
        }
    })
}

function applyCustomer(str) {
    var customer = JSON.parse(str)

    $('#customer_id').val(customer.id)
    $('#btn_customer_id').html(customer.code + ' ' + customer.fullName)
}
</script>
