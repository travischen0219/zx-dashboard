
require('./bootstrap')

import dt from 'datatables.net'
import 'datatables.net-bs4'
import 'datatables.net-buttons'
import 'datatables.net-buttons-bs4'
import 'datatables.net-buttons/js/buttons.colVis.min.js'
import 'datatables.net-buttons/js/buttons.print.min.js'

// window.Vue = require('vue');

$('#sidebar-brand').click(function () {
    $('#sidebar').toggleClass('collapse')
})
