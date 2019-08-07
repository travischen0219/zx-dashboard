@extends('b4.app')

@section('title','採購進貨 - 在途量追蹤 (依照採購單)')
@section('page-header')
    <i class="fas fa-truck active-color mr-2"></i>採購進貨 - 在途量追蹤 (依照採購單)
@endsection

@section('css')
@endsection

@section('content')
    @if (count($ins) == 0)
        <h1>沒有任何在途物料</h1>
    @endif

    <table class="table table-bordered" id="data">
        @foreach($ins as $key => $in)
            <tr class="bg-primary text-white">
                <th>
                    <button type="button" class="btn btn-light mr-3 btn-{{ $key }}" onclick="toggleDetail({{ $key }})">收起</button>
                    單號
                </th>
                <th>批號</th>
                <th>供應商</th>
                <th>日期</th>
                <th>說明</th>
                {{-- <th>在途量</th> --}}
            </tr>

            <tr>
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
                {{-- <td>{{ number_format($m['amounts'][$key2], 2) }}</td> --}}
            </tr>

            @php($total = 0)
            @foreach($in->materials as $material)
                <tr class="detail-{{ $key }}">
                    <th colspan="99">
                        [{{ $material['model']->material_category_name->code }}]
                        {{ $material['model']->material_category_name->name }}：
                        {{ $material['model']->fullCode }}
                        {{ $material['model']->fullName }}
                        <big class="text-primary">在途量：{{ number_format($material['amount'], 2) }}</big>
                    </th>
                </tr>
                @php($total += $material['amount'])
            @endforeach
            {{-- <tr>
                <th colspan="99">
                    <big class="text-primary">在途總量：{{ number_format($total, 2) }}</big>
                </th>
            </tr> --}}

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
