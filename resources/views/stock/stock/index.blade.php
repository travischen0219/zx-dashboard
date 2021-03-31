@extends('b4.app')

@section('title', '庫存 - 入出庫')
@section('page-header')
    <i class="fas fa-archive active-color mr-2"></i> 庫存 - 入出庫
@endsection

@section('css')

@endsection

@section('content')
    <div class="form-group">
        <label class="control-label">入出庫：</label>
        <select class="form-control d-inline-block w-auto" name="way" id="way" onchange="search()">
            @foreach ($ways as $key => $value)
                <option value="{{ $key }}" {{ $key == $way ? 'selected' : ''}}>{{ $value }}</option>
            @endforeach
        </select>

        @if ($way > 0)
            <label class="control-label ml-3">類別：</label>
            <select class="form-control d-inline-block w-auto" name="type" id="type" onchange="search()">
                @foreach ($types as $key => $value)
                    <option value="{{ $key }}" {{ $key == $type ? 'selected' : ''}}>{{ $value }}</option>
                @endforeach
            </select>
        @else
            <input type="hidden" name="type" id="type" value="0">
        @endif

        <label class="control-label ml-3">期間：</label>
        <select name="year" id="year" class="form-control d-inline-block" style="width: 90px;" onchange="search()">
            @for ($i = 2018; $i < date('Y') + 5; $i++)
                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}年</option>
            @endfor
        </select>

        <select name="month" id="month" class="form-control d-inline-block" style="width: 90px;" onchange="search()">
            <option value="0">全部</option>
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ $i }}月</option>
            @endfor
        </select>

    </div>

    <hr>

    @include('includes.messages')

    @if (\App\Model\User::canAdmin('stock'))
        <a href="{{ route('stock.create', ['way' => 1]) }}" class="btn btn-success"><i class="fa fa-plus"></i> 新增入庫</a>
        <a href="{{ route('stock.create', ['way' => 2]) }}" class="btn btn-danger ml-1"><i class="fa fa-plus"></i> 新增出庫</a>
    @endif

    <br><br>

    <table class="table table-bordered" id="data">
        <thead>
            <tr>
                <th nowrap>入出庫</th>
                <th>類別</th>
                <th>日期</th>
                <th>單號</th>
                <th nowrap>批號</th>
                <th>物料</th>
{{--                <th>備註</th>--}}
                <th>數量</th>
                <th nowrap>(前→後) 數量</th>
                <th nowrap>目前庫存</th>
            </tr>
        </thead>
            <tbody>
                @foreach ($stocks as $stock)
                    @php
                        $unit = $stock->material->material_unit_name->name ?? '';
                        $bg = '';
                        $rotate = '';
                        if ($stock->way == 1) {
                            $bg = 'table-success';
                            $rotate = 'rotate-up';
                        }
                        if ($stock->way == 2) {
                            $bg = 'table-danger';
                            $rotate = 'rotate-down';
                        }
                    @endphp
                    <tr class="{{ $bg }}">
                        <td title="入出庫">{{ $ways[$stock->way] ?? '' }}</td>
                        <td title="類別" nowrap>
                            @if ($stock->way == 1)
                                {{ $types1[$stock->type] ?? '' }}
                            @elseif ($stock->way == 2)
                                {{ $types2[$stock->type] ?? '' }}
                            @endif
                        </td>
                        <td title="日期" nowrap>{{ $stock->stock_date }}</td>
                        <td title="採購單號">
                            @if ($stock->way == 1)
                                {{ $stock->in ? 'P' . $stock->in->code : '無' }}
                            @elseif ($stock->way == 2)
                                {{ $stock->out ? 'S' . $stock->out->code : '無' }}
                            @endif
                        </td>
                        <td title="批號">{{ $stock->in->lot->code ?? $stock->lot->code ?? '無' }}</td>
                        <td title="物料">
                            {{ $stock->material->fullCode ?? ''}} {{ $stock->material->fullName ?? ''}}
                            {!! $stock->memo ? '<br><span class="text-muted">備註：' . $stock->memo . "</span>" : '' !!}
                        </td>
{{--                        <td title="備註">{{ $stock->memo ?? ''}}</td>--}}
                        <td title="數量">{{ $stock->amount }}{{ $unit }}</td>
                        <td title="(前→後) 數量" nowrap>{{ $stock->amount_before }}{{ $unit}} <span class="{{ $rotate }}">→</span> {{ $stock->amount_after }}{{ $unit}}</td>
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
        dtOptions.dataLength = 50
        $('#data').DataTable(dtOptions);
    });

    function search() {
        const way = $('#way').val()
        const type = $('#type').val()
        const year = $('#year').val()
        const month = $('#month').val()

        location.href=`/stock/stock/search/${way}/${type}/${year}/${month}`
    }
    </script>
@endsection
