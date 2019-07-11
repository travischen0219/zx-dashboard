{{-- 物料表格 --}}
@include('vue.material_table')

<script>
var app = new Vue({
    el: '#app',
    data: {
        units: {!! $units !!},
        rows: []
    }
})
</script>
