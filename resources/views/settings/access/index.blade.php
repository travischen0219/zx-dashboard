@extends('b4.app')

@section('title','權限設定')
@section('page-header')
@include('settings.company')
@endsection

@section('content')
@include('includes.messages')

<a href="{{ route('access.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增權限角色</a>
<a href="javascript: void(0)" id="save_recoder" class="btn btn-success"><i class="fa fa-check"></i> 儲存排序變更</a>

<form id="this_form">
    {{ csrf_field() }}
    <input type="hidden" name="count_accesses" value="{{count($accesses)}}">

    <table class="table table-striped table-checkable table-bordered table-hover" id="data" style="width: 600px;">
        <thead>
            <tr class="bg-success text-white">
                <th>序 號 (可拖曳排序)</th>
                <th>名 稱</th>
                <th>操 作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accesses as $key => $access)
            <tr class="recoder_tr_{{$key}}" style="cursor: move;">
                <td accessid_{{$key}}="orderby_tb_{{$access->id}}" id="td_id_{{$key}}">{{$access->orderby}}</td>
                <td>{{$access->name}}</td>
                <td align="center"><a href="{{ route('access.edit', $access->id) }}"
                        class="btn btn-outline-primary btn-sm">修改</a>
                    <a href="javascript:;" class="btn btn-outline-danger btn-sm" onclick="
                                if(confirm('確定要刪除嗎 ?')){
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{$access->id}}').submit();
                                } else {
                                    event.preventDefault();
                                }">刪除</a></td>
                <form id="delete-form-{{$access->id}}" action="{{ route('access.destroy', $access->id) }}"
                    method="post">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                </form>
            </tr>
            @endforeach
        </tbody>
    </table>
</form>

@foreach($accesses as $access)
<form id="delete-form-{{$access->id}}" action="{{ route('access.destroy', $access->id) }}" method="post">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
</form>
@endforeach
@endsection

@section('script')
<script>
    $(document).on('click', '#save_recoder',function(e){
        var accesses_length = $("input[type=hidden][name=count_accesses]").val();
        var data_id = [];
        var data_orderby = [];
        for (i = 0; i < accesses_length; i++) {
            var id = $(".recoder_tr_"+i+" td").attr('accessid_'+i).substr(11);
            var orderby = $("#td_id_"+i).html();
            data_id.push(id);
            data_orderby.push(orderby);
        }

        $.post("{{ route('access.update.orderby') }}",
            {
                '_token': "{{csrf_token()}}",
                'data_id': data_id,
                'data_orderby': data_orderby
            },
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
        // dtOptions.ordering = false

        dtOptions.rowReorder = { selector: 'tr' }
        var table = $('#data').DataTable(dtOptions)
    })
</script>
@endsection
