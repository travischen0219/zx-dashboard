@extends('b4.app')

@section('title','客戶資料')
@section('page-header')
    <i class="fas fa-user-tie active-color"></i> 基本資料 - 客戶資料
@endsection

@section('content')

    <form role="form" class="form-inline mb-3" action="{{ route('customers.search') }}" method="POST" id="search_from">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label mr-2">篩選分類：</label>
            <select class="form-control" name="search_category" onchange="search();">
                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                <option value="1" {{ $search_code == 1 ? 'selected' : '' }}>北部</option>
                <option value="2" {{ $search_code == 2 ? 'selected' : '' }}>中部</option>
                <option value="3" {{ $search_code == 3 ? 'selected' : '' }}>南部</option>
                <option value="4" {{ $search_code == 4 ? 'selected' : '' }}>海外</option>
                <option value="5" {{ $search_code == 5 ? 'selected' : '' }}>中國大陸</option>
            </select>
        </div>
    </form>

    @include('includes.messages')
    <a href="{{ route('customers.create') }}" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> 新增客戶</a>

    <table class="table table-striped table-bordered table-hover" id="data" >
        <thead>
            <tr class="bg-primary text-white">
                <th>編 號</th>
                <th>分 類</th>
                <th>全 名</th>
                <th>電 話</th>
                <th>地 址</th>
                <th>操 作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($customers as $customer)
                @if(true)
                <tr>

                    <td>{{$customer->code}}</td>
                    <td>
                        {{ $categories[$customer->category] ?? '未分類' }}
                    </td>

                    <td><a href="{{ route('customers.show', $customer->id) }}">{{$customer->fullName}}</a></td>
                    <td>{{$customer->tel}}</td>
                    <td>{{$customer->address}}</td>
                    <td align="center" id="functions_btn">
                        {{-- <a href="{{ route('customers.show', $customer->id) }}" class="btn purple btn-outline btn-sm">查看</a>                                 --}}
                        <a href="{{ route('customers.edit', $customer->id) }}" class="btn blue btn-outline-primary btn-sm">修改</a>
                        <a href="javascript:;" class="btn red btn-outline-danger btn-sm" onclick="
                            if(confirm('確定要刪除嗎 ?')){
                                event.preventDefault();
                                document.getElementById('delete-form-{{$customer->id}}').submit();
                            } else {
                                event.preventDefault();
                            }">刪除</a>

                        <form id="delete-form-{{$customer->id}}" action="{{ route('customers.destroy', $customer->id) }}" method="post" style="display:none">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

@endsection

@section('script')
    <script>
    function search(){
        $("#search_from").submit();
    }

    $(function () {
        var table = $('#data').DataTable(dtOptions)
    })
    </script>
@endsection
