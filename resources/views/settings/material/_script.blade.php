{{-- 檔案表格 --}}
@include('vue.file_table')

<script>
var app = new Vue({
    el: '#app',
    data: {
        units: {!! $units !!},
        files: {!! $files !!}
    }
})

var categories = {!! JSON_ENCODE($material_categories, JSON_HEX_QUOT | JSON_HEX_TAG) !!};
function getCal(v) {
    if (v != '' && categories[v].cal == '1') {
        $('#cal-column').show();
    } else {
        $('#cal-column').hide();
    }
}

function showFullCode() {

    if($('#code_2').val() == '' || $('#code_2').val() == 'undefiend' || $('#material_category').val() == ''){
        $('#fullCode').html('資料尚未完整');
    } else {
        var material_category = $('#material_category').val();
        var code_1 = $('#code_1').val();
        var code_2 = $('#code_2').val();
        var code_3 = $('#code_3').val();
        var dash_1 = '';

        if(code_3 == ''){
            dash_1 = '';
        } else {
            dash_1 = '-';
        }

        var str = material_category + code_1 + '-' + code_2 + dash_1 + code_3;
        $('#fullCode').html(str);
        $('#fullCode_input').val(str);
    }
}

$(function () {
    // console.log($('#material_category').val())
    getCal($('#material_category').val())
    showFullCode()
})
</script>
