@extends('b4.print')

@section('title','銷貨單')

@section('content')
<style>
    @media screen {
        body {
            padding-top: 100px;
        }

        .container {
            margin-top: 20px;
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

    .table+.table {
        margin-top: 40px;
    }

    input[type=text] {
        width: 150px;
    }

    .navbar {
        height: 87px;
    }

    .table-bordered, .table-bordered th, .table-bordered td, .table-bordered thead th, .table-bordered thead td {
        border: 1px solid #202122;
    }
    .border-left {
        border-left: 1px solid #202122 !important;
    }
    .border-right {
        border-right: 1px solid #202122 !important;
    }
</style>
<nav class="navbar navbar-dark bg-dark fixed-top text-light no-print mb-3">
    <div id="header">
        <h3>列印銷貨報表</h3>
    </div>

    <button class="btn btn-success btn-lg" id="print" onclick="self.print();">
        <i class="fas fa-print"></i> 列印
    </button>
</nav>

@foreach ($outs as $k => $value)
@php($out = $value['out'])
<div class="container">
    <table class="table table-bordered" style="page-break-before: auto;">
        <thead>
            <tr>
                <th colspan="5">
                    <h2 align="center">真心蓮坊股份有限公司</h2>
                </th>
            </tr>
            <tr>
                <th colspan="3" class="text-center align-middle border-right-0">
                    <div style="font-size: 26px;">報價單</div>
                    <div>QUOTATION</div>
                </th>
                <th colspan="2" class="text-left border-left-0">
                    <div>住址：苗栗縣苑裡鎮新復里4鄰45-2號</div>
                    <div>電話：037-864858(專線)</div>
                    <div>傳真：037-868078</div>
                    <div>E-mail：ma.kokoro@msa.hinet.net</div>
                </th>
            </tr>
            <tr>
                <th colspan="99">客戶名稱：{{ $out->customer->fullName ?? '' }}</th>
            </tr>
            <tr>
                <th colspan="3" class="border-right-0">工程名稱：{{ $out->lot->name ?? '' }}</th>
                <th colspan="2" class="text-right border-left-0">{{ date('Y年m月d日'), strtotime($out->created_date) }}
                </th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th class="text-center">項目<br>ITEM</th>
                <th class="text-center">品名規格<br>DESCRIPTION SPECIFICATION</th>
                <th class="text-center">數量<br>QTY</th>
                <th class="text-center">單價<br>UNIT PRICE</th>
                <th class="text-center">小計<br>SUB TOTAL</th>
            </tr>

            @php($outdex = 1)
            @php($total = 0)
            @foreach ($out->material_modules as $key => $material_module)
                <tr style="height: 50px; font-size: 0.8rem;">
                    <td title="項目" class="text-center align-middle">{{ $outdex++ }}</td>
                    <td title="品名規格">
                        <div style="float: left;">
                            {{ $material_module['name'] }} {{ $material_module['memo'] }}
                        </div>
                        <div style="float: right;">
                            <?php
                            $m = \App\Model\Material_module::find($material_module['id']);
                            $file1 = ($m->file_1 > 0) ? \App\Model\StorageFile::find($m->file_1) : false;
                            $file2 = ($m->file_2 > 0) ? \App\Model\StorageFile::find($m->file_2) : false;
                            $file3 = ($m->file_3 > 0) ? \App\Model\StorageFile::find($m->file_3) : false;
                            ?>
                            @if ($file1 || $file2 || $file3)
                                <input type="checkbox" onclick="setFilePrint({{ $m->id }}, $(this))" checked class="file-print no-print">
                            @endif

                            @if ($file1)
                                <img class="f-{{ $m->id }}" src="/storage/files/{{ $file1->file_name }}" height="100" class="mr-1" />
                            @endif

                            @if ($file2)
                                <img class="f-{{ $m->id }}" src="/storage/files/{{ $file2->file_name }}" height="100" class="mr-1" />
                            @endif

                            @if ($file3)
                                <img class="f-{{ $m->id }}" src="/storage/files/{{ $file3->file_name }}" height="100" class="mr-1" />
                            @endif

                        </div>
                    </td>
                    <td title="數量" class="text-right align-middle">{{ number_format($material_module['amount'], 2) }}{{ $material_module['unit'] }}</td>
                    <td title="單價" class="text-right align-middle">{{ number_format($material_module['price'], 2) }}</td>
                    <td title="小計" class="text-right align-middle">
                        {{ number_format($material_module['amount'] * $material_module['price'], 2) }}</td>
                </tr>
                @php($total += $material_module['amount'] * $material_module['price'])
            @endforeach

            <tr>
                <th colspan="99" class="text-right">
                    小計：{{ number_format($total, 2) }}
                    <br>
                    稅：{{ number_format($total * ($out->tax == 1 ? 1.05 : 1) - $total, 2) }}
                    <br>
                    合計：{{ number_format($total * ($out->tax == 1 ? 1.05 : 1), 2) }}
                </th>
            </tr>
            <tr>
                <th colspan="99" class="p-0">
                    <div class="row align-items-center m-0">
                        <div style="height: 100px; line-height: 45px;" class="col-1 py-1 border-right text-center">
                            客戶<br>確認
                        </div>
                        <div style="height: 100px; line-height: 45px;" class="col-5 py-1"></div>
                        <div style="height: 100px; line-height: 45px;"
                            class="col-1 py-1 border-right border-left text-center">
                            主<br>管</div>
                        <div style="height: 100px; line-height: 45px;" class="col-2 py-1 border-right"></div>
                        <div style="height: 100px; line-height: 45px;"
                            class="col-1 py-1 border-right border-left text-center">
                            銷<br>售</div>
                        <div style="height: 100px; line-height: 45px;" class="col-2 py-1"></div>
                    </div>
                </th>
            </tr>
            <tr style="height: 200px;">
                <th colspan="99">
                    <div>銷貨備註內容：<br>{!! nl2br($out->memo) !!}</div>
                </th>
            </tr>
        </tbody>


    </table>
</div>

@endforeach

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

function setFilePrint(id, obj) {
    if (obj.prop('checked')) {
        $(".f-" + id).removeClass('no-print')
    } else {
        $(".f-" + id).addClass('no-print')
    }
}
</script>
@endsection
