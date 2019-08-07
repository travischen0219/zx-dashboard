@extends('b4.app')

@section('title','採購進貨 - 在途量追蹤 (依照物料)')
@section('page-header')
    <i class="fas fa-truck active-color mr-2"></i>採購進貨 - 在途量追蹤 (依照物料)
@endsection

@section('css')
@endsection

@section('content')
    @if (count($ms) == 0)
        <h1>沒有任何在途物料</h1>
    @endif

    <table class="table table-bordered" id="data">
        @foreach($ms as $key => $m)
            <tr class="bg-primary text-white">
                <th colspan="99">
                    <button type="button" class="btn btn-light mr-3 btn-{{ $key }}" onclick="toggleDetail({{ $key }})">收起</button>
                    [{{ $m['material']->material_category_name->code }}]
                    {{ $m['material']->material_category_name->name }}：
                    {{ $m['material']->fullCode }}
                    {{ $m['material']->fullName }}
                    <big class="text-warning float-right">在途總量：{{ number_format(array_sum($m['amounts']), 2) }}</big>
                </th>
            </tr>

            <tr class="table-secondary detail-{{ $key }}">
                <th>單號</th>
                <th>批號</th>
                <th>供應商</th>
                <th>日期</th>
                <th>說明</th>
                <th>在途量</th>
            </tr>

            @foreach($m['ins'] as $key2 => $in)
                <tr class="detail-{{ $key }}">
                    <td>P{{ $in->code }}</td>
                    <td>{!! $in->lot ? $in->lot->code . '<br>' . $in->lot->name : '' !!}</td>
                    <td>
                        {!! $in->supplier ? $in->supplier->code : '' !!}
                        <br>
                        {!! $in->supplier ? $in->supplier->shortName : '' !!}
                    </td>
                    <td>
                        <div>採購日期：{!! $in->buy_date ?? '' !!}</div>
                        <div>預計到貨：{!! $in->should_arrive_date ?? '' !!}</div>
                        <div>實際到貨：{!! $in->arrive_date ?? '' !!}</div>
                    </td>
                    <td class="memo">{{ $in->memo }}</td>
                    <td>{{ number_format($m['amounts'][$key2], 2) }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="99">&nbsp;</td>
            </tr>
        @endforeach
    </table>
@endsection

@section('script')
    <script>
        function toggleDetail(idx) {
            $('.detail-' + idx).fadeToggle('fast')

            if ($('.btn-' + idx).html() == '收起') {
                $('.btn-' + idx).html('展開')
            } else if ($('.btn-' + idx).html() == '展開') {
                $('.btn-' + idx).html('收起')
            }
        }
    </script>
@endsection
