{{-- 物料表格 --}}
@include('vue.material_table')

{{-- 檔案表格 --}}
@include('vue.file_table')

<script>
var app = new Vue({
    el: '#app',
    data: {
        units: {!! $units !!},
        materials: {!! $materials !!},
        files: {!! $files !!}
    }
})
</script>
