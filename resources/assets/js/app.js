
require('./bootstrap')

// import dt from 'datatables.net'
import dt from 'datatables.net-bs4'
import 'datatables.net-buttons-bs4'
import 'datatables.net-buttons/js/buttons.colVis.min.js'
import 'datatables.net-rowreorder-bs4'
import 'magnific-popup'
import 'jquery-ui/ui/widgets/datepicker.js'
import 'busy-load'

import swal from 'sweetalert2';
window.swal = swal

window.Vue = require('vue');

$('#sidebar-brand').click(function () {
    $('#sidebar').toggleClass('collapse')
    $('#content').toggleClass('full')
})
