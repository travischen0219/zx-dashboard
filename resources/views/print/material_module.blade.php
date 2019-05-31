@extends('layouts.print')

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
    </nav>

    @foreach ($modules as $k => $value)
        @php($module = $value['module'])
        <div class="container">
            <h2 align="center">物料模組 ({{ $module->name }}) NO：{{ $module->code }}</h2>
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
                        <th class="text-center">庫存</th>
                        <th class="text-center">備註</th>
                    </tr>

                    @php($index = 1)
                    @foreach ($module->materials as $key => $material)
                        <tr style="height: 50px;">
                            <td title="序號" class="text-center">{{ $index++ }}</td>
                            <td title="品名">{{ $material['code'] }} {{ $material['name'] }}</td>
                            <td title="數量" class="text-right">{{ number_format($material['amount'], 2) }}</td>
                            <td title="單位">{{ $material['unit'] }}</td>
                            <td title="尺寸">{{ $material['size'] }}</td>
                            <td title="顏色">{{ $material['color'] }}</td>
                            <td title="庫存" class="text-right">{{ number_format($material['stock'], 2) }}</td>
                            <td title="備註">{{ nl2br($material['memo']) }}</td>
                        </tr>
                    @endforeach

                    @for ($i = 0; $i < 19 - count($module->materials); $i++)
                        <tr style="height: 50px; display: none;">
                            <td title="項次" class="text-center"></td>
                            <td title="貨品編號"></td>
                            <td title="品名規格"></td>
                            <td title="數量" class="text-right"></td>
                            <td title="單位" class="text-center"></td>
                            <td title="單價" class="text-right"></td>
                            <td title="小計" class="text-right"></td>
                        </tr>
                    @endfor
                </tbody>

            </table>

            <div class="d-flex" style="page-break-after:always">
                <div class="flex-grow-1">製表人：{{session('admin_user')->fullname}}</div>
                <div class="flex-grow-1 text-right">製表日期：{{ date("Y/m/d") }}</div>
            </div>
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
