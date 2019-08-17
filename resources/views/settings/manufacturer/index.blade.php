@extends('b4.app')

@section('title','加工廠商資料')
@section('page-header')
    <i class="fas fa-industry active-color"></i> 基本資料 - 加工廠商資料
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

    <form role="form" action="{{ route('manufacturer.search') }}" method="POST" id="search_from">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label">篩選分類：</label>
            <select class="form-control d-inline-block w-auto" name="search_category" onchange="search();">
                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>常用</option>
                <option value="2" {{$search_code == 2 ? 'selected' : ''}}>不常用</option>
                <option value="" {{$search_code == '' ? 'selected' : ''}}>未設定</option>
            </select>
        </div>
    </form>

    @include('includes.messages')
    <div class="caption font-dark mb-3">
        <a href="{{ route('manufacturer.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增加工廠商</a>
    </div>

    <table class="table table-striped table-bordered table-hover" id="data" >
        <thead>
            <tr class="bg-primary text-white">
                <th width="10%">編 號</th>
                <th width="10%">全 名</th>
                <th width="10%">聯絡人</th>
                <th width="10%">電 話</th>
                <th width="30%">備 註</th>
                <th width="30%">操 作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($manufacturers as $manufacturer)
                @if(true)
                <tr>
                    <td>{{$manufacturer->code}}</td>
                    <td><a href="{{ route('manufacturer.show', $manufacturer->id) }}">{{$manufacturer->fullName}}</a></td>
                    <td>{{$manufacturer->contact}}</td>
                    <td>{{$manufacturer->tel}}</td>
                    <td>{{$manufacturer->memo}}</td>
                    <td align="center" id="functions_btn">
                        <a href="{{ route('manufacturer.edit', $manufacturer->id) }}" class="btn blue btn-outline-primary btn-sm">修改</a>
                        <a href="javascript:;" class="btn red btn-outline-danger btn-sm" onclick="
                            if(confirm('確定要刪除嗎 ?')){
                                event.preventDefault();
                                document.getElementById('delete-form-{{$manufacturer->id}}').submit();
                            } else {
                                event.preventDefault();
                            }">刪除</a>
                        <a href='
                            @if($manufacturer->code != '' && $manufacturer->fullName != '')
                                javascript: barcode("{{$manufacturer->fullName}}", "{{$manufacturer->code}}");' class="btn green btn-outline-success btn-sm">條碼</a>
                            @else
                                javascript:;' class="btn green btn-outline-success btn-sm" disabled>條碼</a>
                            @endif
                        <form id="delete-form-{{$manufacturer->id}}" action="{{ route('manufacturer.destroy', $manufacturer->id) }}" method="post" style="display:none">
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
