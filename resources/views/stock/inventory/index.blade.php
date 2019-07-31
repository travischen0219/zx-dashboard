@extends('b4.app')

@section('title', '庫存盤點 - 盤點')
@section('page-header')
    <i class="fas fa-tasks active-color mr-1"></i> 庫存盤點 - 盤點
@endsection

@section('css')

@endsection


@section('content')
    <div class="form-group">
        <label class="control-label">狀態：</label>
        <select class="form-control d-inline-block w-auto" name="status" onchange="location.href='/stock/inventory/search/' + this.value">
            <option value="0">全部</option>
            @foreach ($statuses as $key => $value)
                <option value="{{ $key }}" {{ $key == $status ? 'selected' : ''}}>{{ $value }}</option>
            @endforeach
        </select>
    </div>

    <hr>

    @include('includes.messages')

    <button type="button" onclick="location.href='{{ route('inventory.create') }}';" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> 新增盤點
    </button>

    <table class="table table-striped table-bordered table-hover" id="data">
        <thead>
            <tr class="bg-success text-white">
                <th>盤點單號</th>
                <th>盤點名稱</th>
                <th>說明</th>
                <th>日期</th>
                <th>倉庫</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($inventories as $inventory)
                <tr>
                    <td class="align-middle">INV{{ $inventory->code }}</td>
                    <td class="align-middle">{{ $inventory->name}}</td>
                    <td class="align-middle"><div class="memo" title="{{ $inventory->memo }}">{{ $inventory->memo }}</div></td>
                    <td class="align-middle">
                        開始：{{ $inventory->start_date }}
                        <br>
                        結束：{{ $inventory->end_date }}
                    </td>
                    <td class="align-middle">{{ $inventory->category_id == 0 ? '全部' : ($inventory->category->code && $inventory->category->name ? "[{$inventory->category->code}] {$inventory->category->name}" : '') }}</td>
                    <td class="align-middle">
                        @if ($inventory->status == 1)
                            <span class="text-danger">盤點中</span>
                        @elseif ($inventory->status == 2)
                            <span class="text-success">已盤點</span>
                        @endif
                        <div class="d-flex justify-content-start align-items-center">
                            進度：
                            <div class="progress" style="width: 100px;">
                                <div class="progress-bar bg-success text-dark"
                                    role="progressbar"
                                    style="width: {{ $inventory->percent() }}%"
                                    aria-valuenow="{{ $inventory->percent() }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{ $inventory->percent() }}%
                                </div>
                            </div>
                        </div>
                    </td>
                    <td align="center" class="align-middle">
                        @if ($inventory->status == 1)
                            <button type="button" onclick="location.href='/stock/inventory/{{ $inventory->id }}/check'" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-list-ol"></i> 盤點
                            </button>

                            <button type="button" onclick="location.href='{{ route('inventory.edit', $inventory->id) }}';" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-pen"></i> 修改
                            </button>

                            <button type="button" onclick="deleteLot({{ $inventory->id }});" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> 刪除
                            </button>
                        @else
                            <button type="button" onclick="location.href='/stock/inventory/{{ $inventory->id }}/view'" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-eye"></i> 查看及誤差處理
                            </button>
                        @endif

                        <form id="delete-form-{{ $inventory->id }}" action="{{ route('inventory.destroy', $inventory->id) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>
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
