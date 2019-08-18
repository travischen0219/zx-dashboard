@extends('b4.app')

@section('title','單位設定')
@section('page-header')
    <i class="fas fa-ruler active-color"></i> 基本資料 - 單位設定
    <small class="text-muted">建立與編輯 (可直接拖曳序號做排序)</small>
@endsection

@section('content')

    @include('includes.messages')
    <a href="{{ route('material_unit.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增單位</a>
    <a href="javascript:;" id="save_recoder" class="btn btn-success"><i class="fa fa-check"></i> 儲存排序變更</a>

    <form id="this_form" class="mb-5" style="width: 800px;">
        {{ csrf_field() }}
        <input type="hidden" name="count_units" value="{{count($material_units)}}">

        <table class="table table-striped table-checkable table-bordered table-hover" id="data">
            <thead>
                <tr class="bg-success text-white">
                    <th>序 號 (可拖曳排序)</th>
                    <th>名 稱</th>
                    <th>操 作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($material_units as $key=>$unit)
                    <tr class="recoder_tr_{{$key}}">
                        <td unitid_{{$key}}="orderby_tb_{{$unit->id}}" id="td_id_{{$key}}">{{$unit->orderby}}</td>
                        <td>{{$unit->name}}</td>

                        <td align="center">
                            <a href="{{ route('material_unit.edit', $unit->id) }}" class="btn blue btn-outline-primary btn-sm">修改</a>

                            <a href="javascript:;" class="btn red btn-outline-danger btn-sm"
                                onclick="if(confirm('確定要刪除嗎 ?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$unit->id}}').submit();
                                        } else {
                                            event.preventDefault();
                                        }"
                                >刪除</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    @foreach($material_units as $unit)
        <form id="delete-form-{{$unit->id}}" action="{{ route('material_unit.destroy', $unit->id) }}" method="post">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
        </form>
    @endforeach

@endsection

@section('script')
<script>
    $(document).on('click', '#save_recoder',function(e){
        var units_length = $("input[type=hidden][name=count_units]").val();
        var data_id = [];
        var data_orderby = [];
        for (i = 0; i < units_length; i++) {
            var id = $(".recoder_tr_"+i+" td").attr('unitid_'+i).substr(11);
            var orderby = $("#td_id_"+i).html();
            data_id.push(id);
            data_orderby.push(orderby);
        }

        $.post(
            "{{ route('material_unit.update.orderby') }}",
            {'_token':"{{csrf_token()}}",'data_id':data_id,'data_orderby':data_orderby},
            function(response){
                if(response == 'success'){
                    swalOption.type = "success"
                    swalOption.title = '存檔成功';
                    swal.fire(swalOption);
                } else if(response == 'error_1'){
                    swalOption.type = "error"
                    swalOption.title = '存檔失敗';
                    swal.fire(swalOption);
                }
            }
        );

        e.preventDefault();
    });

    $(function () {
        dtOptions.paging = false
        dtOptions.info = false
        dtOptions.searching = false
        dtOptions.buttons = []
        dtOptions.order = [[0, 'asc']]
        dtOptions.stateSave = false

        dtOptions.rowReorder = { selector: 'tr' }
        var table = $('#data').DataTable(dtOptions)
    })
</script>
@endsection
