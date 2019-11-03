@extends('b4.print')

@section('title','銷貨報表')

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
    .form label {
        width: auto;
        text-align: initial;
    }
    .table + .table {
        margin-top: 40px;
    }

    input[type=text] {
        width: 150px;
    }
    </style>
    <nav class="navbar navbar-dark bg-dark text-light no-print mb-3">
        <div id="header">
            <h3>列印銷貨報表</h3>

            <form action="/print/out" method="post" class="form mt-3">
                @csrf
                <div class="form-group">
                    <label for="year">期間：</label>
                    <select name="year" id="year" class="form-control d-inline-block" style="width: 90px;">
                        @for ($i = 2018; $i < date('Y') + 5; $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select> 年

                    <select name="month" id="month" class="form-control d-inline-block" style="width: 90px;">
                        <option value="all">全部</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select> 月

                    <label for="lot_id" class="ml-3">
                        批號 (<a href="javascript: resetLot()" class="text-warning">清除</a>)：
                    </label>
                    <button type="button" id="btn_lot_id" class="btn btn-primary" onclick="listLots()">
                        @if (isset($lots[$lot_id]))
                            {{ $lots[$lot_id]->code }} {{ $lots[$lot_id]->name }}
                        @else
                            按此選擇批號
                        @endif
                    </button>

                    <input type="hidden" name="lot_id" id="lot_id" value="{{ $lot_id }}">

                    <label for="supplier_id" class="ml-3">
                        客戶 (<a href="javascript: resetCustomer()" class="text-warning">清除</a>)：
                    </label>
                    <button type="button" id="btn_customer_id" class="btn btn-primary" onclick="listCustomers()">
                        @if (isset($customers[$customer_id]))
                            {{ $customers[$customer_id]->code }} {{ $customers[$customer_id]->shortName }}
                        @else
                            按此選擇供客戶
                        @endif
                    </button>

                    <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer_id }}">
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

                <button class="btn btn-warning px-5 mr-2">
                    <i class="far fa-hand-point-up"></i>
                    產生報表
                </button>
                <span class="text-warning">備註：月份選擇全部則為年報表</span>
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
                            <div class="col-4"></div>
                            <div class="col-4 text-center" style="font-size: 24px;">
                                {{ $year }}年{{ $month != 'all' ? $month . '月' : '' }}銷貨報表
                            </div>
                            <div class="col-4 text-right"><small>列印日期：{{ date('Y/m/d') }}</small></div>
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

                @php($outdex = 1)
                @foreach ($outs as $key1 => $out)
                    @foreach ($out->material_modules as $key2 => $material_module)
                        <tr>
                            @if(in_array(0, $selColumns))<td title="項次" class="text-center">{{ $outdex++ }}</td>@endif
                            @if(in_array(1, $selColumns))<td title="銷貨日期" class="text-center">{{ $out->created_date ?? '' }}</td>@endif
                            @if(in_array(2, $selColumns))<td title="批號">{{ $out->lot->code ?? '' }}</td>@endif
                            @if(in_array(3, $selColumns))<td title="客戶">{{ $out->customer->shortName ?? '' }}</td>@endif
                            @if(in_array(4, $selColumns))<td title="編號">{{ $material_module['code'] }}</td>@endif
                            @if(in_array(5, $selColumns))<td title="品名">{{ $material_module['name'] }}</td>@endif
                            @if(in_array(6, $selColumns))
                                <td title="銷貨數量" class="text-right">
                                    {{ number_format($material_module['amount'], 2) }}
                                </td>
                            @endif
                            @if(in_array(7, $selColumns))<td title="單位成本" class="text-right">${{ number_format($material_module['cost'], 2) }}</td>@endif
                            @if(in_array(8, $selColumns))<td title="單價" class="text-right">${{ number_format($material_module['price'], 2) }}</td>@endif
                            @if(in_array(9, $selColumns))<td title="金額" class="text-right">${{ number_format($material_module['amount'] * $material_module['price'], 2) }}</td>@endif
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <div class="">
            <div class="text-right">總金額：${{ number_format($total_price) }}</div>
            <div class="text-right">- 總成本：${{ number_format($total_cost) }}</div>
            <div class="text-right">= 總利潤：${{ number_format($total_price - $total_cost) }}</div>
        </div>

        <hr>

        <div class="d-flex">
            <div class="flex-grow-1">製表人：{{session('admin_user')->fullname}}</div>
            <div class="flex-grow-1 text-right">製表日期：{{ date("Y/m/d") }}</div>
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="z-index: 6500;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <iframe id="modal-iframe" frameBorder="0" src="" height="100%" width="100%"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
<script>
function tapCol(obj) {
    if (obj.prop('checked')) {
        obj.parent().addClass('btn-primary').removeClass('btn-light');
    } else {
        obj.parent().removeClass('btn-primary').addClass('btn-light');
    }
}

function listLots() {
    $("#modal .modal-title").html("選擇批號")
    $("#modal-iframe").attr("src", "")
    $("#modal-iframe").attr("src", "/selector/lot")
    $("#modal-iframe").height('70vh')
    $('#modal').modal('show')
}

function listCustomers() {
    $("#modal .modal-title").html("選擇客戶")
    $("#modal-iframe").attr("src", "")
    $("#modal-iframe").attr("src", "/selector/customer")
    $("#modal-iframe").height('70vh')
    $('#modal').modal('show')
}

function applyLot(str) {
    var lot = JSON.parse(str)

    $('#lot_id').val(lot.id)
    $('#btn_lot_id').html(lot.code + ' ' + lot.name)

    $('#modal').modal('hide')
}

function applyCustomer(str) {
    var customer = JSON.parse(str)

    $('#customer_id').val(customer.id)
    $('#btn_customer_id').html(customer.code + ' ' + customer.fullName)
    $('#modal').modal('hide')
}

function resetCustomer() {
    $('#customer_id').val(0)
    $('#btn_customer_id').html('按此選擇客戶')
}
function resetLot() {
    $('#lot_id').val(0)
    $('#btn_lot_id').html('按此選擇批號')
}
</script>
@endsection
