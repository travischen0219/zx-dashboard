@extends('b4.selector')

@section('title','入庫紀錄')


@section('content')
    <style>
        #category, #category option {
            font-family: 'Courier New';
        }
    </style>
    <div class="container-fluid">
        <h2>
            入庫紀錄
        </h2>
        <hr>

        <table id="data" class="table table-bordered table-striped table-hover mt-3">
            <thead>
                <tr class="bg-primary text-white">
                    <th>入庫日期</th>
                    <th>採購單號</th>
                    <th>批號</th>
                    <th>物料</th>
                    <th>數量</th>
                    <th>入庫 (前->後) 數量</th>
                    <th>庫存</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stocks as $stock)
                    @php($unit = $stock->material->material_unit_name->name)
                    <tr>
                        <td title="入庫日期">{{ $stock->stock_date }}</td>
                        <td title="採購單號">P{{ $stock->in->code }}</td>
                        <td title="批號">{{ $stock->in->lot->code }}</td>
                        <td title="物料">{{ $stock->material->fullCode}} {{ $stock->material->fullName}}</td>
                        <td title="數量">{{ $stock->amount }}{{ $unit}}</td>
                        <td title="入庫 (前->後) 數量">{{ $stock->amount_before }}{{ $unit}} -> {{ $stock->amount_after }}{{ $unit}}</td>
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
