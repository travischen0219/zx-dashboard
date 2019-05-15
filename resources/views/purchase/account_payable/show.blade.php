@extends('layouts.app')

@section('title','應付帳款')

@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<style>
    a {
        text-decoration: none;
    }

    #sample_3 td {
        font-size: 16px;
        vertical-align: middle;
    }

    #sample_3 th {
        font-size: 16px;
        vertical-align: middle;
    }

    #functions_btn {
        text-align: center;
    }

    table thead {
        color: #fff;
        background-color: #248ff1;
    }

    #sample_3_filter input {
        width: 300px !important;
    }


    .search_form .form-group {
        margin-left: 20px;
    }

    .search_label {
        font-size: 16px;
    }

    #search_supplier,
    #search_code,
    #search_lot_number {
        font-size: 14px;
        width: 150px;
        display: inline-block;
    }

    .search_submit {
        background-color: #248ff1;
        color: #fff;
        font-size: 16px;
    }

    .table.dataTable thead .sorting,
    .table.dataTable thead .sorting_desc,
    .table.dataTable thead .sorting_asc {
        background-image: none;
    }
</style>

@endsection

@section('page_header')

<div class="page-bar">
    @include('layouts.theme_panel')
    <h1 class="page-title"> 應付帳款
        <small></small>
    </h1>
</div>

@endsection

@section('content')

<div class="row">
    <form role="form" action="{{ route('account_payable.search') }}" method="POST"
        class="col-md-12 search_form form-inline">
        {{ csrf_field() }}

        <div class="form-group">
            <label class="search_label">供應商：</label>
            <select class="form-control" name="search_supplier" id="search_supplier">
                <option value="all" {{$search_supplier == 'all' ? 'selected' : ''}}>全部</option>
                @foreach ($suppliers as $supplier)
                <option value="{{ $supplier['id'] }}" {{$search_supplier == $supplier['id'] ? 'selected' : ''}}>
                    {{ $supplier['code'] }} {{ $supplier['shortName'] }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label class="search_label">狀態：</label>
            <select class="form-control" name="search_code" id="search_code">
                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>未付款</option>
                <option value="2" {{$search_code == 2 ? 'selected' : ''}}>已付款</option>
                <option value="3" {{$search_code == 3 ? 'selected' : ''}}>取消</option>
            </select>
        </div>

        <div class="form-group">
            <label class="search_label">批號：</label>
            <input type="text" class="form-control" name="search_lot_number" id="search_lot_number"
                value="{{ $search_lot_number }}">
        </div>

        <div class="form-group">
            <button type="submit" class="btn search_submit">搜 尋</button>
        </div>
    </form>

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark">
                    <a href="{{ route('account_payable.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>
                        新增應付帳款
                    </a>
                </div>
                <div class="tools"></div>
            </div>

            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_3">
                    <thead>
                        <tr>
                            <th>供應商</th>
                            <th>會計單號</th>
                            <th>批號</th>
                            <th>採購單號</th>
                            <th>開單日</th>
                            <th>付款日</th>
                            <th>應付金額</th>
                            <th>狀態</th>
                            <th>操 作</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($account_payables as $account_payable)
                        <tr>
                            <td>{{$account_payable->code}} {{$account_payable->shortName}}</td>
                            <td>AP{{$account_payable->account_payable_no}}</td>
                            <td>{{$account_payable->lot_number}}</td>
                            @if($account_payable->buy_no == '0')
                            <td></td>
                            @elseif($account_payable->buy_no != '')
                            <td>P{{$account_payable->buy_no}}</td>
                            @else
                            <td></td>
                            @endif
                            <td align="center">{{$account_payable->createDate}}</td>
                            <td align="center">{{$account_payable->payDate}}</td>
                            <td title="應付金額" align="right">${{$account_payable->total_pay}}</td>
                            <td>
                                @if($account_payable->status == '1') <span style="color:red">未付款</span>
                                @elseif($account_payable->status == '2') <span style="color:green">已付款</span>
                                @elseif($account_payable->status == '3') <span style="color:blue">取消</span>
                                @endif
                            </td>
                            <td align="center" id="functions_btn">
                                @if($account_payable->status == 1)
                                <a href="{{ route('account_payable.edit', $account_payable->id) }}"
                                    class="btn blue btn-outline btn-sm">修改</a>
                                @elseif($account_payable->status == 2 || $account_payable->status == 3)
                                <a href="{{ route('account_payable.edit', $account_payable->id) }}"
                                    class="btn purple btn-outline btn-sm">查看</a>
                                @endif
                                <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                                        if(confirm('確定要刪除嗎 ?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$account_payable->id}}').submit();
                                        } else {
                                            event.preventDefault();
                                        }">刪除</a>
                                <form id="delete-form-{{$account_payable->id}}"
                                    action="{{ route('account_payable.destroy', $account_payable->id) }}" method="post"
                                    style="display:none">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->

    </div>
</div>

@endsection

@section('scripts')
<script src="{{asset('assets/global/scripts/datatable.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/pages/scripts/table-datatables-buttons.js')}}" type="text/javascript"></script>

<script>
    $(function () {
        var table = $('#sample_3').DataTable();
        table.button('2').remove();
        table.button('1').remove();
        table.button('0').remove();

        @if ($search_supplier != 'all')
            // 動態加入列印按鈕

            table.button().add(0, {
                action: function (e, dt, button, config) {
                    window.open('/purchase/account_payable/print/{{ $search_supplier }}');
                },
                text: '列印 (' + $("#search_supplier option:selected").text() + ') 的未付款資料',
                className: 'btn dark btn-outline'
            });
        @endif
    });
</script>
@endsection
