@extends('b4.selector')

@section('title','入出庫紀錄')

@section('content')
    <style>
        body {
            background-color: #fff;
        }
        #category, #category option {
            font-family: 'Courier New';
        }
    </style>
    <div class="container-fluid">
        <table id="data" class="table table-bordered mt-3">
            <thead>
                <tr class="bg-primary text-white">
                    <th>入出庫</th>
                    <th>類別</th>
                    <th>日期</th>
                    {{-- <th>單號</th> --}}
                    <th>批號</th>
                    <th>物料</th>
                    <th>數量</th>
                    <th>(前->後) 數量</th>
                    <th>庫存</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stocks as $stock)
                    @php
                        $unit = $stock->material->material_unit_name->name ?? '';
                        $bg = '';
                        if ($stock->way == 1) $bg = 'table-success';
                        if ($stock->way == 2) $bg = 'table-danger';
                    @endphp
                    <tr class="{{ $bg }}">
                        <td title="入出庫">{{ $ways[$stock->way] ?? '' }}</td>
                        <td title="類別">
                            @if ($stock->way == 1)
                                {{ $types1[$stock->type] ?? '' }}
                            @elseif ($stock->way == 2)
                                {{ $types2[$stock->type] ?? '' }}
                            @endif
                        </td>
                        <td title="日期">{{ $stock->stock_date }}</td>
                        {{-- <td title="單號">
                            @if ($stock->way == 1)
                                {{ $stock->in ? 'P' . $stock->in->code : '無' }}
                            @elseif ($stock->way == 2)
                                {{ $stock->in ? 'S' . $stock->out->code : '無' }}
                            @endif
                        </td> --}}
                        <td title="批號">{{ $stock->in->lot->code ?? '' }}</td>
                        <td title="物料">{{ $stock->material->fullCode}}<br>{{ $stock->material->fullName}}</td>
                        <td title="數量">{{ $stock->amount }}{{ $unit}}</td>
                        <td title="(前->後) 數量">{{ $stock->amount_before }}{{ $unit}} -> {{ $stock->amount_after }}{{ $unit}}</td>
                        <td title="庫存">{{ $stock->material->stock }}{{ $unit}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
    $(function() {
        $('#data').DataTable({
            "language": {
                "url": '/json/datatable.zh-tw.json'
            }
        });
    });
    </script>
@endsection
