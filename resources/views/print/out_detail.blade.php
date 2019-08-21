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
    .table + .table {
        margin-top: 40px;
    }

    input[type=text] {
        width: 150px;
    }

    .navbar {
        height: 87px;
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
            <h2 align="center">真⼼蓮坊股份有限公司</h2>
            <table class="table table-bordered" style="page-break-after:always">
                <thead>
                    <tr>
                        <th colspan="99">
                            <div class="row align-items-center">
                                <div class="col-4">
                                    <div>苗栗縣苑裡鎮新復⾥ 4 鄰 45-2 號</div>
                                    <small>TEL：(037) 864858 FAX：(037) 868078</small>
                                </div>
                                <div class="col-4 text-center" style="font-size: 24px;">
                                    銷貨單
                                </div>
                                <div class="col-4 text-right">
                                    <div>NO：S{{ $out->code }}</div>
                                    <div>批號：{{ $out->lot->code ?? '' }}</div>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="99">
                            <div class="row align-items-center">
                                <div class="col-4" style="font-size: 18px;">
                                    客戶：{{ $out->customer->shortName ?? '' }}
                                </div>
                                <div class="col-8 text-right">
                                    銷貨日期：{{ date('Y年m月d日'), strtotime($out->created_date) }}
                                </div>
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <th class="text-center">項次</th>
                        <th class="text-center">貨品編號</th>
                        <th class="text-center">品 名 規 格</th>
                        <th class="text-center">數量</th>
                        <th class="text-center">單價</th>
                        <th class="text-center">小計</th>
                    </tr>

                    @php($outdex = 1)
                    @foreach ($out->material_modules as $key => $material_module)
                        <tr style="height: 50px;">
                            <td title="項次" class="text-center">{{ $outdex++ }}</td>
                            <td title="貨品編號">{{ $material_module['code'] }}</td>
                            <td title="品名規格">{{ $material_module['name'] }}</td>
                            <td title="數量" class="text-right">{{ number_format($material_module['amount'], 2) }}</td>
                            <td title="單價" class="text-right">{{ number_format($material_module['price'], 2) }}</td>
                            <td title="小計" class="text-right">{{ number_format($material_module['amount'] * $material_module['price'], 2) }}</td>
                        </tr>
                    @endforeach

                    @for ($i = 0; $i < 19 - count($out->material_modules); $i++)
                        <tr style="height: 50px;">
                            <td title="項次" class="text-center"></td>
                            <td title="貨品編號"></td>
                            <td title="品名規格"></td>
                            <td title="數量" class="text-right"></td>
                            <td title="單價" class="text-right"></td>
                            <td title="小計" class="text-right"></td>
                        </tr>
                    @endfor
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="99" class="p-0">
                            <div class="row align-items-center m-0">
                                <div class="col-1 py-1 border-right text-center">客戶<br>確認</div>
                                <div class="col-3 py-1"></div>
                                <div class="col-1 py-1 border-right border-left text-center">主<br>管</div>
                                <div class="col-3 py-1 border-right"></div>
                                <div class="col-1 py-1 border-right border-left text-center">採<br>購</div>
                                <div class="col-3 py-1"></div>
                            </div>
                        </th>
                    </tr>
                    <tr style="height: 200px;">
                        <th colspan="99">
                            <div>銷貨備註內容：{{ nl2br($out->memo) }}</div>
                        </th>
                    </tr>
                </tfoot>

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
</script>
@endsection
