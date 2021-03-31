@extends('b4.app')

@section('title','供應商')
@section('page-header')
    <i class="fas fa-industry active-color"></i> 基本資料 - 供應商
@endsection

@section('css')
<style>
    #popup{
    width:400px;
    height:160px;
    display:block;
    background-color: white;
    margin:auto;
    }
    #popup p{
        padding-top:20px;
        display:block;
        text-align:center;
    }
    #popup img{
        display:block;
        margin:0 auto ;
        padding:0px 20px;
    }
</style>

@endsection


@section('content')

    <form role="form" action="{{ route('supplier.search') }}" method="POST" id="search_from">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label"> 標籤 :</label>
            <select class="mr-2 form-control d-inline-block w-auto" style="font-size: 14px;" name="search_tag" onchange="search();">
                <option value="all" {{$search_tag == 'all' ? 'selected' : ''}}>全部</option>
                <option value="supplier" {{$search_tag == 'supplier' ? 'selected' : ''}}>供應商</option>
                <option value="manufacturer" {{$search_tag == 'manufacturer' ? 'selected' : ''}}>加工商</option>
            </select>

            <label class="control-label"> 篩選分類 :</label>
            <select class="form-control d-inline-block w-auto" style="font-size: 14px;" name="search_category" onchange="search();">
                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>常用</option>
                <option value="2" {{$search_code == 2 ? 'selected' : ''}}>不常用</option>
                <option value="none" {{$search_code == 'none' ? 'selected' : ''}}>未設定</option>
            </select>
        </div>
    </form>

    @include('includes.messages')
    <div class="caption font-dark mb-3">
        @if (\App\Model\User::canAdmin('settings'))
            <a href="{{ route('supplier.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增供應商</a>
        @endif
    </div>

    <table class="table table-striped table-bordered table-hover" id="data" >
        <thead>
            <tr class="bg-primary text-white">
                <th width="10%">標 籤</th>
                <th width="10%">分 類</th>
                <th width="10%">編 號</th>
                <th width="10%">全 名</th>
                <th width="10%">聯絡人</th>
                <th width="10%">電 話</th>
                <th width="30%">備 註</th>
                <th width="30%">操 作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($suppliers as $supplier)
                @if(true)
                <tr>
                    <td>
                        @if ($supplier->supplier == 1)
                            <span class="badge badge-primary p-1">供應商</span>
                        @endif

                        @if ($supplier->manufacturer == 1)
                            <span class="badge badge-success p-1">加工商</span>
                        @endif
                    </td>
                    <td>
                        @if ($supplier->category == 1)
                            常用
                        @elseif ($supplier->category == 2)
                            不常用
                        @elseif ($supplier->category == null)
                            未設定
                        @endif
                    </td>
                    <td>{{$supplier->code}}</td>
                    <td><a href="{{ route('supplier.show', $supplier->id) }}">{{$supplier->fullName}}</a></td>
                    <td>{{$supplier->contact}}</td>
                    <td>{{$supplier->tel}}</td>
                    <td>{{$supplier->memo}}</td>
                    <td align="center" id="functions_btn" nowrap>
                        @if (\App\Model\User::canAdmin('settings'))
                            {{-- <a href="{{ route('supplier.show', $supplier->id) }}" class="btn purple btn-outline btn-sm">查看</a> --}}
                            <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn blue btn-outline-primary btn-sm">修改</a>
                            <a href="javascript:;" class="btn red btn-outline-danger btn-sm" onclick="
                                if(confirm('確定要刪除嗎 ?')){
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{$supplier->id}}').submit();
                                } else {
                                    event.preventDefault();
                                }">刪除</a>
                            <a href='
                                @if($supplier->code != '' && $supplier->fullName != '')
                                    javascript: barcode("{{$supplier->fullName}}", "{{$supplier->code}}");' class="btn green btn-outline-success btn-sm">條碼</a>
                                @else
                                    javascript:;' class="btn green btn-outline-success btn-sm" disabled>條碼</a>
                                @endif
                            <form id="delete-form-{{$supplier->id}}" action="{{ route('supplier.destroy', $supplier->id) }}" method="post" style="display:none">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                            </form>
                        @endif
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <p class="mb-5"></p>
@endsection

@section('script')
<script>
function barcode(title, code) {
    $.magnificPopup.open({
        showCloseBtn : false,
        enableEscapeKey : false,
        closeOnBgClick: true,
        fixedContentPos: false,
        modal:false,
        type:'ajax',
        items:{src:"{{route('barcode')}}"},
        ajax: {
            settings: {
                type: 'GET',
                data: {
                    title: title, code: code
                }
            }
        }
    });
}

function search(){
    $("#search_from").submit();
}

$(function () {
    var table = $('#data').DataTable(dtOptions)
})
</script>
@endsection
