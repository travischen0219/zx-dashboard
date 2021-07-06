@extends('b4.app')

@section('title','物料模組')
@section('page-header')
    <i class="fab fa-buromobelexperte active-color"></i> 基本資料 - 物料模組
@endsection

@section('css')
    <style>
    .search-label {
        color: #248ff1;
        font-size: 16px;
        line-height: 32px;
        text-align: center;
        margin-right: 10px;
    }
    #search_code {
        margin-right: 10px;
    }
    .btn-search {
        font-size: 16px;
    }
    </style>
@endsection

@section('content')
    @include('includes.messages')

    @if (\App\Model\User::canAdmin('settings'))
        <a href="{{ route('material_module.create') }}" class="btn btn-primary mb-3"><i class="fa fa-plus"></i> 新增物料模組</a>
    @endif
    <table class="table table-striped table-bordered table-hover" id="data" >
        <thead>
            <tr class="bg-primary text-white">
                <th>列印</th>
                <th>編號</th>
                <th>名稱</th>
                <th>單位</th>
                <th>價錢</th>
                <th>產品說明</th>
                <th>操 作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($modules as $material_module)

                <tr>
                    <td>
                        <a href="/print/material_module/{{ $material_module->id }}" target="_blank" class="btn blue btn-outline-primary btn-sm">
                            <i class="fas fa-print"></i> 列印
                        </a>
                    </td>
                    <td>{{$material_module->code}}</td>
                    <td><a href="{{ route('material_module.show', $material_module->id) }}">{{$material_module->name}}</a></td>
                    <td>{{$material_module->material_unit_name->name ?? ''}}</td>
                    <td>
                        價錢：${{number_format($material_module->price, 2)}}
                        <br>
                        成本：${{number_format($material_module->total_cost, 2)}}
                    </td>
                    <td>{{$material_module->memo}}</td>

                    <td align="center" id="functions_btn">
                        @if (\App\Model\User::canAdmin('settings'))
                            {{-- <a href="{{ route('material_module.show', $material_module->id) }}" class="btn purple btn-outline btn-sm">查看</a> --}}
                            <a href="{{ route('material_module.duplicate', $material_module->id) }}" class="btn blue btn-outline-success btn-sm">
                                <i class="fas fa-copy"></i> 複製
                            </a>
                            <a href="{{ route('material_module.edit', $material_module->id) }}" class="btn blue btn-outline-primary btn-sm">
                                <i class="fas fa-pen"></i> 修改
                            </a>
                            <a href="javascript:;" class="btn red btn-outline-danger btn-sm" onclick="
                                if(confirm('確定要刪除嗎 ?')){
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{$material_module->id}}').submit();
                                } else {
                                    event.preventDefault();
                                }"><i class="fas fa-trash-alt"></i> 刪除</a>
                            <form id="delete-form-{{$material_module->id}}" action="{{ route('material_module.destroy', $material_module->id) }}" method="post" style="display:none">
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
    </script>
@endsection
