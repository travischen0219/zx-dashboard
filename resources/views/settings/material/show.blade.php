@extends('b4.app')

@section('title','物料管理')
@section('page-header')
    <i class="fas fa-puzzle-piece active-color mr-1"></i>基本資料 - 物料管理
    <small class="text-muted">資料建立與編輯</small>
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
    #pop_stock{
        width:85%;
        height:900px;
        display:block;
        background-color: white;
        margin:auto;
    }
    .mfp-wrap {
        z-index: 9998;
    }
</style>

@endsection


@section('content')

    <form role="form" action="{{ route('materials.search') }}" method="POST" id="search_from">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label"> 篩選分類 :</label>
            <select class="form-control d-inline-block w-auto" name="search_category" onchange="search();">

                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                @foreach($material_categories as $cate)
                    <option value="{{$cate->code}}" {{ $search_code == $cate->code ? 'selected' : '' }}>[ {{$cate->code}} ] {{$cate->name}}</option>
                @endforeach

            </select>
        </div>
    </form>

    @include('includes.messages')
    <div class="mb-3">
        <a href="{{ route('materials.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增物料</a>
        <span class="btn btn-primary" onclick="pdfsubmit();"><i class="fa fa-print"></i> 多筆PDF列印</span>
    </div>

    <table class="table table-striped table-bordered table-hover" id="data">
        <thead>
            <tr class="bg-primary text-white">
                <th>編 號</th>
                <th>列 印</th>
                <th>分 類</th>
                <th>品 名</th>
                <th>單 位</th>
                <th>尺 寸</th>
                <th>安全量</th>
                <th>庫 存</th>
                <th>操 作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($materials as $material)
                <tr>
                    <td>{{$material->fullCode}}</td>
                    <td>
                        <a href="{{url('barcode_PDF/'.$material->id)}}" target="_blank" class="btn blue btn-outline-primary btn-sm">列印</a>&nbsp;&nbsp;
                        <input type="checkbox" class="print_pdf" name="print_pdf"  value="{{$material->id}}">
                    </td>
                    <td>
                        @if($material->material_categories_code == '')
                            <span style="color:red;">未指派</span>
                        @else
                            [ {{$material->material_categories_code}} ] {{$material->material_category_name->name}}
                        @endif
                    </td>

                    <td><a href="{{ route('materials.show', $material->id) }}">{{$material->fullName}}</a></td>
                    <td>
                        @if($material->unit > 0 )
                            {{$material->material_unit_name->name}}
                        @else
                            <span style="color:red;">未指派</span>
                        @endif
                    </td>
                    <td>{{$material->size}}</td>

                    @if($material->safe >0)
                        <td>{{$material->safe}}</td>
                    @else
                        <td><span style="color:red;">未設定</span></td>
                    @endif

                    @if($material->safe >= $material->stock )
                        <td style="">
                        <font color="red">{{$material->stock}}</font>
                    @else
                        <td >
                        {{$material->stock}}
                    @endif

                        <a href="javascript: show_stock('{{ $material->id }}');" class="btn blue btn-outline-primary btn-sm pull-right">庫存紀錄</a>
                    </td>
                    <td align="center" id="functions_btn">
                        {{-- <a href="{{ route('materials.show', $material->id) }}" class="btn purple btn-outline btn-sm">查看</a> --}}
                        <a href="{{ route('materials.edit', $material->id) }}" class="btn blue btn-outline-primary btn-sm">修改</a>
                        <a href="javascript:;" class="btn red btn-outline-danger btn-sm" onclick="
                            if(confirm('確定要刪除嗎 ?')){
                                event.preventDefault();
                                document.getElementById('delete-form-{{$material->id}}').submit();
                            } else {
                                event.preventDefault();
                            }">刪除</a>
                        <a href='
                            @if($material->fullCode != '' && $material->fullName != '')
                                javascript: barcode("{{$material->fullName}}", "{{$material->fullCode}}");' class="btn green btn-outline-success btn-sm">條碼</a>
                            @else
                                javascript:;' class="btn green btn-outline btn-sm" disabled>條碼</a>
                            @endif
                        <form id="delete-form-{{$material->id}}" action="{{ route('materials.destroy', $material->id) }}" method="post" style="display:none">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>
                    </td>
                </tr>
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

function show_stock(id) {
    $.magnificPopup.open({
        showCloseBtn : true,
        enableEscapeKey : false,
        closeOnBgClick: false,
        fixedContentPos: true,
        modal:true,
        type:'ajax',
        items:{src:"/settings/show_stock/" + id},
        ajax: {
            settings: {
                type: 'GET'
            }
        }
    });
}

function close_show_stock(){
    $.magnificPopup.close();
}

function search(){
    $("#search_from").submit();
}

function pdfsubmit()
{
    var chkArray = [];

    $(".print_pdf:checked").each(function() {
        chkArray.push($(this).val());
    });

    /* we join the array separated by the comma */
    var selected;
    selected = chkArray.join(',');
    var url = "{!!url('barcode_PDF')!!}"+"/"+selected;
    openInNewTab(url);
}

function openInNewTab(url) {
    var win = window.open(url, '_blank');
    win.focus();
}

$(function () {
    var table = $('#data').DataTable(dtOptions)
})
</script>
@endsection
