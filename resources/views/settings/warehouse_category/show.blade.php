@extends('b4.app')

@section('title','倉儲分類設定')
@section('page-header')
    <i class="fas fa-warehouse active-color"></i> 基本資料 - 倉儲分類設定
    <small class="text-muted">建立與編輯 (可直接拖曳序號做排序)</small>
@endsection

@section('content')
    @include('includes.messages')
    <a href="{{ route('warehouse_category.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增分類</a>
    <a href="javascript:;" id="save_recoder" class="btn btn-success"><i class="fa fa-check"></i> 儲存排序變更</a>

    <form id="this_form" class="my-3" style="width: 600px;">
        {{ csrf_field() }}
        <input type="hidden" name="count_cates" value="{{count($cates)}}">

        <table class="table table-striped table-checkable table-bordered table-hover" id="data">
            <thead>
                <tr class="bg-success text-white">
                    <th>序 號 (可拖曳排序)</th>
                    <th>名 稱</th>
                    <th>狀 態</th>
                    <th>操 作</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cates as $key=>$cate)
                    <tr class="recoder_tr_{{$key}}">
                        <td cateid_{{$key}}="orderby_tb_{{$cate->id}}" id="td_id_{{$key}}">{{$cate->orderby}}</td>
                        <td>{{$cate->name}}</td>
                        <td>
                            @if($cate->status == 1)
                                <span style="color:blue;">啟用</span>
                            @elseif($cate->status == 2)
                                <span style="color:red;">關閉</span>
                            @endif
                        </td>

                        <td align="center"><a href="{{ route('warehouse_category.edit', $cate->id) }}" class="btn blue btn-outline-primary btn-sm">修改</a>
                            <a href="javascript:;" class="btn red btn-outline-danger btn-sm" onclick="
                                if(confirm('確定要刪除嗎 ?')){
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{$cate->id}}').submit();
                                } else {
                                    event.preventDefault();
                                }">刪除</a></td>
                            <form id="delete-form-{{$cate->id}}" action="{{ route('warehouse_category.destroy', $cate->id) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>

    @foreach($cates as $cate)
        <form id="delete-form-{{$cate->id}}" action="{{ route('warehouse_category.destroy', $cate->id) }}" method="post">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
    @endforeach
@endsection

@section('script')

<script>
    $(document).on('click', '#save_recoder',function(e){
        var cates_length = $("input[type=hidden][name=count_cates]").val();
        var data_id = [];
        var data_orderby = [];
        for (i = 0; i < cates_length; i++) {
            var id = $(".recoder_tr_"+i+" td").attr('cateid_'+i).substr(11);
            var orderby = $("#td_id_"+i).html();
            data_id.push(id);
            data_orderby.push(orderby);
        }
        $.post(
            "{{ route('warehouse_category.update.orderby') }}",
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

        dtOptions.rowReorder = { selector: 'tr' }
        var table = $('#data').DataTable(dtOptions)
    })
</script>
@endsection
