@extends('b4.app')

@section('title', $title)
@section('page-header')
    <i class="fas fa-archive active-color mr-2"></i> {{ $title }}
@endsection

@section('css')
    <style>
        .memo {
            white-space: nowrap;
            width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .mfp-wrap {
            z-index: 8000;
        }
        .mfp-iframe-holder .mfp-content {
            width: 85%;
            height: 85%;
            max-width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="form-group">
        <label class="control-label">類別：</label>
        <select class="form-control d-inline-block w-auto" name="type" onchange="location.href='/stock/stock/search/' + this.value">
            @foreach ($types as $key => $value)
                <option value="{{ $key }}" {{ $key == $type ? 'selected' : ''}}>{{ $value }}</option>
            @endforeach
        </select>
    </div>

    <hr>

    @include('includes.messages')

    <a href="{{ route('stock.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增入庫</a>

    <br><br>

    <table class="table table-bordered" id="data">
        <thead>
            <tr>
                <th>類別</th>
                <th>入庫日期</th>
                <th>採購單號</th>
                <th>批號</th>
                <th>物料</th>
                <th>數量</th>
                <th>入庫 (前→後) 數量</th>
                <th>目前庫存</th>
            </tr>
        </thead>
            <tbody>
                @foreach ($stocks as $stock)
                    @php
                        $unit = $stock->material->material_unit_name->name ?? '';
                        $bg = '';
                        if ($stock->type == 1) $bg = 'table-primary';
                        if ($stock->type == 2) $bg = 'table-success';
                        if ($stock->type == 3) $bg = 'table-warning';
                    @endphp
                    <tr class="{{ $bg }}">
                        <td title="類別">{{ $types[$stock->type] }}</td>
                        <td title="入庫日期">{{ $stock->stock_date }}</td>
                        <td title="採購單號">{{ $stock->in ? 'P' . $stock->in->code : '無' }}</td>
                        <td title="批號">{{ $stock->in->lot->code ?? $stock->lot->code ?? '無' }}</td>
                        <td title="物料">{{ $stock->material->fullCode ?? ''}} {{ $stock->material->fullName ?? ''}}</td>
                        <td title="數量">{{ $stock->amount }}{{ $unit}}</td>
                        <td title="入庫 (前→後) 數量">{{ $stock->amount_before }}{{ $unit}} → {{ $stock->amount_after }}{{ $unit}}</td>
                        <td title="目前庫存">{{ $stock->material->stock ?? 0 }}{{ $unit}}</td>
                    </tr>
                @endforeach
            </tbody>
    </table>

    <br><br>
@endsection

@section('script')
    <script>
    $(function() {
        $('#data').DataTable(dtOptions);
    });
    </script>
@endsection
