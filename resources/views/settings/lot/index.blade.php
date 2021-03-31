@extends('b4.app')

@section('title', '批號管理')
@section('page-header')
    <i class="fas fa-tasks active-color mr-1"></i>基本資料 - 批號管理
    <small class="text-muted">批號建立與編輯</small>
@endsection

@section('css')
    <style>
    .memo {
        white-space: nowrap;
        width: 100px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    </style>
@endsection


@section('content')
    @if (\App\Model\User::canAdmin('settings'))
        <button type="button" onclick="location.href='{{ route('lot.create') }}';" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> 新增批號
        </button>
    @endif
    <table class="table table-striped table-bordered table-hover" id="data">
        <thead>
            <tr class="bg-success text-white">
                <th>批號</th>
                <th>案件名稱</th>
                <th>客戶</th>
                <th>日期</th>
                <th>進度</th>
                <th>狀態</th>
                <th>備註</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($lots as $lot)
                <tr>
                    <td>{{ $lot->code }}</td>
                    <td>{{ $lot->name}}</td>
                    <td>{{ $lot->customer_id ? $customers[$lot->customer_id]->shortName : '' }}</td>
                    <td>
                        開始：{{ $lot->start_date }}
                        <br>
                        結束：{{ $lot->end_date }}
                    </td>
                    <td>{{ $lot->status }}</td>
                    <td>{!! $lot->is_finished == 1 ? '<span class="text-success">已完工</span>' : '進行中' !!}</td>
                    <td><div class="memo" title="{{ $lot->memo }}">{{ $lot->memo }}</div></td>
                    <td align="center">
                        @if (\App\Model\User::canAdmin('settings'))
                            <button type="button" onclick="location.href='{{ route('lot.edit', $lot->id) }}';" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-pen"></i> 修改
                            </button>
                            <button type="button" onclick="deleteLot({{ $lot->id }});" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> 刪除
                            </button>

                            <form id="delete-form-{{ $lot->id }}" action="{{ route('lot.destroy', $lot->id) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection

@section('script')
    <script>
    $(function () {
        var table = $('#data').DataTable(dtOptions)
    })

    function deleteLot(id) {
        if(confirm('確定要刪除嗎 ?')){
            $('#delete-form-' + id).submit()
        }
    }
    </script>
@endsection
