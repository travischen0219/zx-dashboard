@extends('b4.print')

@section('title','未收款資料')

@section('content')
    <style>
    @media screen {
        body {
            padding-top: 120px;
        }
    }

    #header label {
        margin-bottom: 0;
    }
    .table-bordered td, .table-bordered th {
        border: 1px solid dimgray;
    }
    .table + .table {
        margin-top: 40px;
    }
    </style>
    <nav class="navbar fixed-top navbar-dark bg-dark text-light no-print">
        <div id="header">
            <h3>列印未收款資料</h3>
            <div>
                客戶：
                @foreach ($customerKeys as $customerKey)
                    <label class="btn btn-primary mr-1">
                        <input type="checkbox"
                            name="customer"
                            class="customer"
                            id="customer_{{ $customerKey }}"
                            value="{{ $customerKey }}"
                            checked
                            onclick="tapCustomer($(this))">
                        {{ $customers[$customerKey]['shortName'] }}
                    </label>
                @endforeach

                {{-- <button class="btn btn-light btn-sm ml-5" onclick="selectAll();">全選</button>
                <button class="btn btn-light btn-sm ml-2" onclick="selectNone();">全不選</button> --}}
            </div>
        </div>
        <button class="btn btn-success btn-lg float-right" onclick="self.print();">
            <i class="fas fa-print"></i> 列印
        </button>
    </nav>

    <div class="container">
        <h1 align="center">未收款資料</h1>

        @foreach ($unpays as $key => $unpay)
            <table class="table table-bordered" id="table-{{ $key }}">
                <tr>
                    <th rowspan="{{ count($unpay) + 2 }}" width="15%" class="align-middle text-center">
                        <div>{{ $customers[$key]['code'] }}</div>
                        <div>{{ $customers[$key]['shortName'] }}</div>
                    </th>
                    <th width="20%">批號</th>
                    <th width="20%">銷貨單號</th>
                    <th width="15%" class="text-right">金額</th>
                    <th width="15%" class="text-right">已收</th>
                    <th width="15%" class="text-right">剩餘</th>
                </tr>

                @php($subTotal = 0)
                @php($subPay = 0)
                @foreach ($unpay as $detail)
                    <tr>
                        <td>{{ $detail->lot->code ?? '' }}</td>
                        <td>{{ $detail->code ? 'P' . $detail->code : '' }}</td>
                        <td class="text-right">${{ number_format($detail->total_price, 2) }}</td>
                        <td class="text-right">${{ number_format($detail->total_pay, 2) }}</td>
                        <td class="text-right">${{ number_format($detail->total_price - $detail->total_pay, 2) }}</td>
                    </tr>
                    @php($subTotal += $detail->total_price)
                    @php($subPay += $detail->total_pay)
                @endforeach

                <tr>
                    <td colspan="5" class="text-right">
                        應收：${{ number_format($subTotal, 2) }}
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        已收：${{ number_format($subPay, 2) }}
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        剩餘：${{ number_format($subTotal - $subPay, 2) }}
                    </td>
                </tr>
            </table>
        @endforeach

        <div class="d-flex">
            <div class="flex-grow-1">製表人：{{session('admin_user')->fullname}}</div>
            <div class="flex-grow-1 text-right">製表日期：{{ date("Y/m/d") }}</div>
        </div>
    </div>

    <script>
    function tapCustomer(obj) {
        if (obj.prop('checked')) {
            obj.parent().addClass('btn-primary').removeClass('btn-light');

            $("#table-" + obj.val()).show();
        } else {
            obj.parent().removeClass('btn-primary').addClass('btn-light');

            $("#table-" + obj.val()).hide();
        }
    }

    function selectAll() {
        $(".customer").prop('checked', true);
    }

    function selectNone() {
        $(".customer").prop('checked', false);
    }
    </script>
@endsection
