@extends('b4.app')

@section('title','職稱設定')
@section('page-header')
    @include('settings.company')
    {{-- <i class="fas fa-id-card-alt active-color"></i> 基本資料 - 職稱設定
    <small class="text-muted">建立與編輯 (可直接拖曳序號做排序)</small> --}}
@endsection

@section('content')


<div class="row">
    <div class="col-md-8">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark col-md-12">
                    <a href="{{ route('professional_title.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增職稱</a>
                    {{--  <button type="submit" class="btn purple">儲存排序變更</button>  --}}
                    {{--  <a href="javascript:;" onclick="event.preventDefault();document.getElementById('this_form').submit();" class="btn purple">儲存排序變更</a>  --}}
                    <a href="javascript:;" id="save_recoder" class="btn btn-success"><i class="fa fa-check"></i> 儲存排序變更</a>
                </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <form id="this_form">
                    {{ csrf_field() }}
                    <input type="hidden" name="count_titles" value="{{count($pro_titles)}}">

                    <table class="table table-striped table-checkable table-bordered table-hover" id="data">
                        <thead>
                            <tr class="bg-success text-white">
                                <th>序 號 (可拖曳排序)</th>
                                <th>職 稱</th>
                                <th>操 作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pro_titles as $key=>$pro_title)
                                <tr class="recoder_tr_{{$key}}" style="cursor: move;">
                                    <td titleid_{{$key}}="orderby_tb_{{$pro_title->id}}" id="td_id_{{$key}}">{{$pro_title->orderby}}</td>
                                    <td>{{$pro_title->name}}</td>
                                    <td align="center"><a href="{{ route('professional_title.edit', $pro_title->id) }}" class="btn blue btn-outline-primary btn-sm">修改</a>
                                        <a href="javascript:;" class="btn red btn-outline-danger btn-sm" onclick="
                                            if(confirm('確定要刪除嗎 ?')){
                                                event.preventDefault();
                                                document.getElementById('delete-form-{{$pro_title->id}}').submit();
                                            } else {
                                                event.preventDefault();
                                            }">刪除</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>

                @foreach($pro_titles as $pro_title)
                    <form id="delete-form-{{$pro_title->id}}" action="{{ route('professional_title.destroy', $pro_title->id) }}" method="post">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                    </form>
                @endforeach

            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->

        <button id="success_alert" class="btn btn-primary mt-sweetalert" data-title="存檔成功" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-primary" style="display: none"></button>
        <button id="error_1_alert" class="btn btn-primary mt-sweetalert" data-title="尚無資料需要排序" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-primary" style="display: none"></button>
    </div>
</div>




@endsection

@section('script')
    <script>
        $(document).on('click', '#save_recoder',function(e){
            var titles_length = $("input[type=hidden][name=count_titles]").val();
            var data_id = [];
            var data_orderby = [];
            for (i = 0; i < titles_length; i++) {
                var id = $(".recoder_tr_"+i+" td").attr('titleid_'+i).substr(11);
                var orderby = $("#td_id_"+i).html();
                data_id.push(id);
                data_orderby.push(orderby);
            }

            $.post(
                "{{ route('professional_title.update.orderby') }}",
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
