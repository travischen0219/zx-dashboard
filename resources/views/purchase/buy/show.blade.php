@extends('b4.app')

@section('title','採購')
@section('page-header')
    <i class="fas fa-shopping-cart active-color mr-2"></i>採購進貨 - 採購
@endsection

@section('css')
    <style>
        #loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{asset('assets/apps/img/loader_icon.gif')}}") 50% 50% no-repeat rgb(249, 249, 249);
            background-size: 120px 120px;
        }
    </style>
@endsection

@section('content')
    <div id="loader"></div>

    <form role="form" class="form-inline" action="{{ route('buy.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-group">
            <label class="control-label">狀態：</label>
            <select class="form-control" name="search_category">
                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>未採購</option>
                <option value="2" {{$search_code == 2 ? 'selected' : ''}}>已採購</option>
                <option value="3" {{$search_code == 3 ? 'selected' : ''}}>已到貨</option>
                <option value="11" {{$search_code == 11 ? 'selected' : ''}}>轉半成品</option>
                <option value="4" {{$search_code == 4 ? 'selected' : ''}}>已轉到入庫</option>
            </select>
        </div>

        <div class="form-group ml-3">
            <label class="control-label">批號：</label>
            <input type="text" class="form-control" name="search_lot_number" id="search_lot_number">
            <button type="submit" class="btn btn-primary ml-3">搜 尋</button>
        </div>
    </form>

    <hr>

    @include('includes.messages')

    <a href="{{ route('buy.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增採購</a>
    <span class="btn btn-primary mr-2" onclick="pdfsubmit();"><i class="fa fa-print"></i> 多筆列印</span>
    全選 <input type="checkbox" class="checkAll" id="checkAll" value="1">

    <br><br>

    <table class="table table-striped table-bordered table-hover" id="data">
        <thead>
            <tr>
                <th>列印</th>
                <th>單號</th>
                <th>批號</th>
                <th>供應商</th>
                <th>說明</th>
                <th>採購日期</th>
                <th>預計到貨日</th>
                <th>實際到貨日</th>
                <th>狀態</th>
                <th>操 作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($buys as $buy)

            <tr>
                <td>
                    <input type="checkbox" class="print_pdf" name="print_pdf" value="{{$buy->id}}">
                    <a href="/print/buy_detail/{{ $buy->id }}" target="_blank"
                        class="btn blue btn-outline-primary btn-sm">列印</a>
                </td>
                <td>P{{$buy->buy_no}}</td>
                <td>{{$buy->lot_number}}</td>
                <td>{{$buy->supplier_name->shortName}}</td>
                <td>{{$buy->memo}}</td>
                <td>{{$buy->buyDate}}</td>
                <td>{{$buy->expectedReceiveDate}}</td>
                <td>{{$buy->realReceiveDate}}</td>
                <td>
                    @if($buy->status == '1')
                    @if($buy->status_return == 1 || $buy->status_return == 2)
                    <span style="color:red">未採購<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                    document.getElementById('search-form-{{$buy->id}}').submit();">
                            (退貨)</a></span>
                    <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}"
                        method="post" style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @elseif($buy->status_exchange == 1 || $buy->status_exchange == 2)
                    <span style="color:red">未採購<a href="javascript:;" style="color:red;"
                            onclick="event.preventDefault();
                                    document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>
                    <form id="search-exchange-form-{{$buy->id}}"
                        action="{{ route('p_exchange.search_exchange') }}" method="post"
                        style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @else
                    <span style="color:red">未採購</span>
                    @endif

                    @elseif($buy->status == '2')
                    @if($buy->status_exchange == 1 || $buy->status_exchange == 2)
                    <span style="color:blue">已採購<a href="javascript:;" style="color:red;"
                            onclick="event.preventDefault();
                                    document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>
                    <form id="search-exchange-form-{{$buy->id}}"
                        action="{{ route('p_exchange.search_exchange') }}" method="post"
                        style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @elseif($buy->status_return == 1 || $buy->status_return == 2)
                    <span style="color:blue">已採購<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                    document.getElementById('search-form-{{$buy->id}}').submit();">
                            (退貨)</a></span>
                    <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}"
                        method="post" style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @else
                    <span style="color:blue">已採購</span>
                    @endif

                    @elseif($buy->status == '3')
                    @if($buy->status_exchange == 1 || $buy->status_exchange == 2)
                    <span style="color:purple">已到貨<a href="javascript:;" style="color:red;"
                            onclick="event.preventDefault();
                                    document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>
                    <form id="search-exchange-form-{{$buy->id}}"
                        action="{{ route('p_exchange.search_exchange') }}" method="post"
                        style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @elseif($buy->status_return == 1 || $buy->status_return == 2)
                    <span style="color:purple">已到貨<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                    document.getElementById('search-form-{{$buy->id}}').submit();">
                            (退貨)</a></span>
                    <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}"
                        method="post" style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @else
                    <span style="color:purple">已到貨</span>
                    @endif

                    @elseif($buy->status == '11')
                    @if($buy->status_exchange == 1 || $buy->status_exchange == 2)
                    <span style="color:#248ff1">轉半成品<a href="javascript:;" style="color:red;"
                            onclick="event.preventDefault();
                                    document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>
                    <form id="search-exchange-form-{{$buy->id}}"
                        action="{{ route('p_exchange.search_exchange') }}" method="post"
                        style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @elseif($buy->status_return == 1 || $buy->status_return == 2)
                    <span style="color:#248ff1">轉半成品<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                    document.getElementById('search-form-{{$buy->id}}').submit();">
                            (退貨)</a></span>
                    <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}"
                        method="post" style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @else
                    <span style="color: #248ff1">轉半成品</span>
                    @endif

                    @elseif($buy->status == '4')

                    @if($buy->status_exchange == 1 || $buy->status_exchange == 2)
                    <span style="color:green">已轉到入庫<a href="javascript:;" style="color:red;"
                            onclick="event.preventDefault();
                                    document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>
                    <form id="search-exchange-form-{{$buy->id}}"
                        action="{{ route('p_exchange.search_exchange') }}" method="post"
                        style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @elseif($buy->status_return == 1 || $buy->status_return == 2)
                    <span style="color:green">已轉到入庫<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                    document.getElementById('search-form-{{$buy->id}}').submit();">
                            (退貨)</a></span>
                    <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}"
                        method="post" style="display:none">
                        {{ csrf_field() }}
                        <input type="hidden" name="buy_id" value="{{$buy->id}}">
                    </form>
                    @else
                    <span style="color:green">已轉到入庫</span>
                    @endif
                    @endif
                </td>
                <td align="center" id="functions_btn">
                    @if($buy->status == 1 || $buy->status == 2 || $buy->status == 3 || $buy->status == 11)
                    <a href="{{ route('buy.edit', $buy->id) }}" class="btn blue btn-outline-primary btn-sm">修改</a>
                    <a href="javascript:;" class="btn red btn-outline-danger btn-sm" onclick="
                                if(confirm('確定要刪除嗎 ?')){
                                    event.preventDefault();
                                    document.getElementById('delete-form-{{$buy->id}}').submit();
                                } else {
                                    event.preventDefault();
                                }">刪除</a>
                    <form id="delete-form-{{$buy->id}}" action="{{ route('buy.destroy', $buy->id) }}"
                        method="post" style="display:none">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                    </form>
                    @elseif($buy->status == 4)
                    <a href="{{ route('buy.edit', $buy->id) }}" class="btn purple btn-outline-success btn-sm">查看</a>
                    @endif
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>

    <br><br>
@endsection

@section('script')
<script>
    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    function pdfsubmit() {

        var chkArray = [];

        $(".print_pdf:checked").each(function () {
            chkArray.push($(this).val());
        });

        /* we join the array separated by the comma */
        var selected;
        selected = chkArray.join(',');
        openInNewTab("/print/buy_details/" + selected);
    }

    function openInNewTab(url) {
        var win = window.open(url, '_blank');
        win.focus();
    }

    $(function () {
        $("#loader").fadeOut("slow")
        var table = $('#data').DataTable(dtOptions)
    })
</script>
@endsection
