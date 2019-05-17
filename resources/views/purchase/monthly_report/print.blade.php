@extends('layouts.print')

@section('title','採購月報表')

@section('content')
    <style>
    @media screen {
        body {
            padding-top: 0px;
        }
    }
    #header {
        position: relative;
    }
    #header label {
        margin-bottom: 0;
    }
    #print {
        position: absolute;
        top: 20px;
        right: 20px;
    }
    /* .table-bordered td, .table-bordered th {
        border: 1px solid dimgray;
    } */
    .table + .table {
        margin-top: 40px;
    }

    input[type=text] {
        width: 150px;
    }
    </style>
    <nav class="navbar navbar-dark bg-dark text-light no-print mb-3">
        <div id="header">
            <h3>列印採購月報表</h3>

            <form action="/purchase/monthly_report/print" method="post" class="form mt-3">
                @csrf
                <div class="form-group">
                    <label for="startDate">起始日期：</label>
                    <input type="text"
                        name="startDate"
                        id="startDate"
                        value="{{ $startDate }}"
                        class="form-control d-inline-block datepicker">

                    <label for="endDate" class="ml-3">結束日期：</label>
                    <input type="text"
                        name="endDate"
                        id="endDate"
                        value="{{ $endDate }}"
                        class="form-control d-inline-block datepicker">
                </div>

                <div class="form-group">
                    <label for="lot_number">批號：</label>
                    <input type="text"
                        name="lot_number"
                        id="lot_number"
                        value="{{ $lot_number }}"
                        class="form-control d-inline-block">

                    <label for="supplier" class="ml-3">供應商：</label>
                    <select name="supplierID" id="supplierID" class="form-control d-inline-block" style="width: 200px;">
                        <option value="">全部</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ $supplierID == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->code }} {{ $supplier->shortName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>輸出欄位：</label>
                    @foreach ($columns as $key => $column)
                        <label class="btn {{ in_array($key, $selColumns) ? 'btn-primary' : 'btn-light' }}  mr-1">
                            <input type="checkbox"
                                name="selColumns[]"
                                class="column"
                                id="column_{{ $key }}"
                                value="{{ $key }}"
                                {{ in_array($key, $selColumns) ? 'checked' : '' }}
                                onclick="tapCol($(this))">
                            {{ $column }}
                        </label>
                    @endforeach
                </div>

                <button class="btn btn-light">產生報表</button>
            </form>
        </div>

        <button class="btn btn-success btn-lg" id="print" onclick="self.print();">
            <i class="fas fa-print"></i> 列印
        </button>
    </nav>

    <div class="container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="99">
                        <div class="row align-items-center">
                            <div class="col-4">列印日期：{{ date('Y/m/d') }}</div>
                            <div class="col-4 text-center" style="font-size: 24px;">採購月報表</div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th colspan="99">
                        <div class="row align-items-center">
                            <div class="col-4"></div>
                            <div class="col-4 text-center">
                                範圍：{{ $startDate }} ～ {{ $endDate }}
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    @foreach ($columns as $key => $column)
                        @if(in_array($key, $selColumns))
                            <th>{{ $column }}</th>
                        @endif
                    @endforeach
                </tr>

                @php($index = 1)
                @foreach ($buys as $key1 => $buy)
                    @foreach ($buy->materials as $key2 => $material)
                        <tr>
                            @if(in_array(0, $selColumns))<td title="項次" class="text-center">{{ $index++ }}</td>@endif
                            @if(in_array(1, $selColumns))<td title="批號">{{ $buy->lot_number }}</td>@endif
                            @if(in_array(2, $selColumns))<td title="廠商">{{ $suppliers[$buy->supplier]->shortName }}</td>@endif
                            @if(in_array(3, $selColumns))<td title="編號">{{ $material['code'] }}</td>@endif
                            @if(in_array(4, $selColumns))<td title="品名">{{ $material['name'] }}</td>@endif
                            @if(in_array(5, $selColumns))<td title="採購數量" class="text-right">{{ number_format($material['calAmount'], 2) }}</td>@endif
                            @if(in_array(6, $selColumns))<td title="進貨數量" class="text-right">{{ number_format($material['amount'], 2) }}</td>@endif
                            @if(in_array(7, $selColumns))<td title="單價" class="text-right">${{ number_format($material['price'], 2) }}</td>@endif
                            @if(in_array(8, $selColumns))<td title="金額" class="text-right">${{ number_format($material['amount'] * $material['price'], 2) }}</td>@endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- <div class="container">
        <h1 align="center">未付款資料</h1>

        @foreach ($unpays as $key => $unpay)
            <table class="table table-bordered" id="table-{{ $key }}">
                <tr>
                    <th rowspan="{{ count($unpays) }}" width="25%" class="align-middle text-center">
                        <div>{{ $suppliers[$key]['code'] }}</div>
                        <div>{{ $suppliers[$key]['shortName'] }}</div>
                    </th>
                    <th width="25%">批號</th>
                    <th width="25%">採購單號</th>
                    <th width="25%" class="text-right">金額</th>
                </tr>

                @php($subTotal = 0)
                @foreach ($unpay as $detail)
                    <tr>
                        <td>{{ $detail['lot_number'] }}</td>
                        <td>P{{ $detail['buy_no'] }}</td>
                        <td class="text-right">${{ number_format($detail['totals'], 2) }}</td>
                    </tr>
                    @php($subTotal += $detail['totals'])
                @endforeach

                <tr>
                    <td colspan="3" class="text-right">小計：${{ number_format($subTotal, 2) }}</td>
                </tr>
            </table>
        @endforeach

        <div class="d-flex">
            <div class="flex-grow-1">製表人：{{session('admin_user')->fullname}}</div>
            <div class="flex-grow-1 text-right">製表日期：{{ date("Y/m/d") }}</div>
        </div>
    </div> --}}

@endsection


@section('script')
<script>
$(function() {
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        regional: 'zh-TW'
    });
});

function tapCol(obj) {
    if (obj.prop('checked')) {
        obj.parent().addClass('btn-primary').removeClass('btn-light');
    } else {
        obj.parent().removeClass('btn-primary').addClass('btn-light');
    }
}
</script>
@endsection
