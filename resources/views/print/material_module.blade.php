@extends('b4.print')

@section('title','物料模組')

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
        right: 180px;
    }
    #excel {
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
            <h3>列印物料模組</h3>
        </div>

        <button class="btn btn-success btn-lg" id="print" onclick="self.print();">
            <i class="fas fa-print"></i> 列印
        </button>
        <button class="btn btn-primary btn-lg" id="excel" onclick="location.href='/print/material_module_excel/{{ $id }}';">
            <i class="fas fa-file-excel"></i> 匯出 Excel
        </button>
    </nav>

    @foreach ($modules as $k => $value)
        @php($module = $value['module'])
        <div class="container">
            <h2 align="center">物料模組 ({{ $module->name }})<br>NO：{{ $module->code }}</h2>
            <h4 align="center">{{ nl2br($module->memo) }}</h3>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="text-center">序號</th>
                        <th class="text-center">品名</th>
                        <th class="text-center">數量</th>
                        <th class="text-center">單位</th>
                        <th class="text-center">尺寸</th>
                        <th class="text-center">顏色</th>
                        <th class="text-center">成本</th>
                        <th class="text-center">備註</th>
                    </tr>

                    @php($index = 1)
                    @php($cost_total = 0)
                    @foreach ($module->materials as $key => $material)
                        @php($m = \App\Model\Material::find($material['id']))

                        <tr style="height: 50px;">
                            <td title="序號" class="text-center">{{ $index++ }}</td>
                            <td title="品名">{{ $material['code'] }} {{ $material['name'] }}</td>
                            <td title="數量" class="text-right">{{ number_format($material['amount'], 2) }}</td>
                            <td title="單位" class="text-center">{{ $material['unit'] }}</td>
                            <td title="尺寸">{{ $material['size'] }}</td>
                            <td title="顏色">{{ $material['color'] }}</td>
                            {{-- <td title="庫存" class="text-right">{{ number_format($material['stock'], 2) }}</td> --}}
                            <td title="成本" class="text-right">{{ number_format($m->cost, 2) }}</td>
                            <td title="備註">{{ nl2br($material['memo']) }}</td>
                        </tr>

                        @php($cost_total += ($m->cost * $material['amount']))
                    @endforeach

                    <tr>
                        <th colspan="99" class="text-right">
                            成本合計：{{ number_format($cost_total, 2) }}
                        </th>
                    </tr>

                    <tr>
                        <th colspan="99" class="p-0">
                            <div class="row align-items-center m-0">
                                <div class="col-1 py-4 border-right text-center">董事長</div>
                                <div class="col-3 py-4"></div>
                                <div class="col-1 py-4 border-right border-left text-center">總經理</div>
                                <div class="col-3 py-4"></div>
                                <div class="col-1 py-4 border-right border-left text-center">經辦人</div>
                                <div class="col-3 py-4"></div>
                            </div>
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
</script>
@endsection
