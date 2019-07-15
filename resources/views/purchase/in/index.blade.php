@extends('b4.app')

@section('title','採購')
@section('page-header')
    <i class="fas fa-shopping-cart active-color mr-2"></i>採購進貨 - 採購
@endsection

@section('css')
    <style>
        #loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{asset('assets/apps/img/loader_icon.gif')}}") 50% 50% no-repeat rgb(249, 249, 249);
            background-size: 120px 120px;
        }

        .memo {
            white-space: nowrap;
            width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection

@section('content')
    @php
        $na = '<span class="text-muted">未選</span>';
    @endphp

    <div id="loader"></div>

    <div class="form-group">
        <label class="control-label">狀態：</label>
        <select class="form-control d-inline-block w-auto" name="status" onchange="location.href='/purchase/in/search/' + this.value">
            <option value="">全部</option>
            @foreach ($statuses as $key => $value)
                <option value="{{ $key }}" {{ $key == $status ? 'selected' : ''}}>{{ $value }}</option>
            @endforeach
        </select>
    </div>

    <hr>

    @include('includes.messages')

    <a href="{{ route('in.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增採購</a>
    <span class="btn btn-primary mr-2" onclick="pdfsubmit();"><i class="fa fa-print"></i> 多筆列印</span>
    全選 <input type="checkbox" class="checkAll" id="checkAll" value="1">

    <br><br>

    <table class="table table-striped table-bordered table-hover" id="data">
        <thead>
            <tr>
                <th>列印</th>
                <th>單號</th>
                <th>批號</th>
                <th>廠商</th>
                <th>日期</th>
                <th>狀態</th>
                <th>付款</th>
                <th>說明</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ins as $in)

                <tr>
                    <td>
                        <input type="checkbox" class="print_pdf" name="print_pdf" value="{{ $in->id }}">
                        <a href="/print/in/{{ $in->id }}" target="_blank"
                            class="btn blue btn-outline-primary btn-sm">列印</a>
                    </td>
                    <td>P{{ $in->code }}</td>
                    <td>
                        {!! $in->lot ? $in->lot->code . '<br>' . $in->lot->name : $na !!}
                    </td>
                    <td>
                        <div>供應商：{!! $in->supplier ? $in->supplier->shortName : $na !!}</div>
                        <div>加工廠商：{!! $in->manufacturer ? $in->manufacturer->shortName : $na !!}</div>
                    </td>
                    <td>
                        <div>採購日期：{!! $in->buy_date ?? $na !!}</div>
                        <div>預計到貨：{!! $in->should_arrive_date ?? $na !!}</div>
                        <div>實際到貨：{!! $in->arrive_date ?? $na !!}</div>
                    </td>
                    <td>{{ $statuses[$in->status] ?? '' }}</td>
                    <td>
                        @php
                            $tr_total_cost = App\Model\Material_module::getTotalCost($in->materials) ?? 0;
                            $tr_total_pay = App\Model\Pay::getTotalPay($in->pays) ?? 0;
                        @endphp
                        應付：${{ number_format($tr_total_cost) }}
                        <br>
                        實付：${{ number_format($tr_total_pay) }}
                        <br>
                        @if ($tr_total_cost - $tr_total_pay <= 0)
                            剩餘：<span class="text-success">付清</span>
                        @else
                            剩餘：<span class="text-danger">${{ number_format($tr_total_cost - $tr_total_pay) }}</span>
                        @endif
                    </td>
                    <td><div class="memo" title="{{ $in->memo }}">{{ $in->memo }}</div></td>
                    <td align="center">
                        {{-- @if ($in->status == 30 || $in->status == 40) --}}
                        @if (false)
                            <button type="button" onclick="location.href='/purchase/in/{{ $in->id }}';" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-eye"></i> 查看
                            </button>
                        @else
                            <button type="button" onclick="location.href='{{ route('in.edit', $in->id) }}';" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-pen"></i> 修改
                            </button>
                            <button type="button" onclick="deleteIn({{ $in->id }});" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> 刪除
                            </button>

                            <form id="delete-form-{{ $in->id }}" action="{{ route('in.destroy', $in) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <input type="hidden" name="referrer" value="{{ URL::current() }}">
                            </form>
                        @endif
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>

    <br><br>
@endsection

@section('script')
<script>
    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    function pdfsubmit() {

        var chkArray = [];

        $(".print_pdf:checked").each(function () {
            chkArray.push($(this).val());
        });

        var selected;
        selected = chkArray.join(',');
        openInNewTab("/print/buy_details/" + selected);
    }

    function openInNewTab(url) {
        var win = window.open(url, '_blank');
        win.focus();
    }

    function deleteIn(id) {
        if(confirm('確定要刪除嗎 ?')){
            $('#delete-form-' + id).submit()
        }
    }

    $(function () {
        $("#loader").fadeOut("slow")
        var table = $('#data').DataTable(dtOptions)
    })
</script>
@endsection
