@extends('b4.app')

@section('title','銷貨')
@section('page-header')
    <i class="fas fa-dolly-flatbed active-color mr-2"></i>銷貨出貨 - 銷貨
@endsection

@section('css')
    <style>

    </style>
@endsection

@section('content')
    @php
        $na = '<span class="text-muted">未選</span>';
    @endphp

    <div class="form-group">
        <label class="control-label">狀態：</label>
        <select class="form-control d-inline-block w-auto" name="status" onchange="location.href='/shopping/out/search/' + this.value + '/{{ $pay_status }}'">
            <option value="0">全部</option>
            @foreach ($statuses as $key => $value)
                <option value="{{ $key }}" {{ $key == $status ? 'selected' : ''}}>{{ $value }}</option>
            @endforeach
        </select>

        <label class="control-label ml-3">收款：</label>
        <select class="form-control d-inline-block w-auto" name="status" onchange="location.href='/shopping/out/search/{{ $status }}/' + this.value">
            <option value="0">全部</option>
            @foreach ($pay_statuses as $key => $value)
                <option value="{{ $key }}" {{ $key == $pay_status ? 'selected' : ''}}>{{ $value }}</option>
            @endforeach
        </select>
    </div>

    <hr>

    @include('includes.messages')

    @if (\App\Model\User::canAdmin('shopping'))
        <a href="{{ route('out.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增銷貨</a>
    @endif
    {{-- <span class="btn btn-primary mr-2" onclick="pdfsubmit();"><i class="fa fa-print"></i> 多筆列印</span> --}}
    {{-- <label for="checkAll">全選</label> <input type="checkbox" class="checkAll" id="checkAll" value="1"> --}}

    <br><br>

    <table class="table table-striped table-bordered table-hover" id="data" style="font-size: .8rem;">
        <thead>
            <tr>
                <th nowrap>列印</th>
                <th>單號</th>
                <th>批號</th>
                <th nowrap>客戶</th>
                <th>日期</th>
                <th>狀態</th>
                <th>利潤</th>
                <th>收款</th>
                <th>說明</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outs as $out)

                <tr>
                    <td>
                        {{-- <input type="checkbox" class="print_pdf" name="print_pdf" value="{{ $out->id }}"> --}}
                        <a href="/print/out_detail/{{ $out->id }}" target="_blank" style="font-size: .8rem;"
                            class="btn blue btn-outline-primary btn-sm">列印</a>
                    </td>
                    <td>S{{ $out->code }}</td>
                    <td>
                        {!! $out->lot ? $out->lot->code . '<br>' . $out->lot->name : $na !!}
                        @if ($out->project != '')
                            <div>工：{{ $out->project }}</div>
                        @endif
                    </td>
                    <td>
                        {!! $out->customer ? $out->customer->shortName : $na !!}
                    </td>
                    <td nowrap>
                        <div>新增：{!! $out->created_date ?? $na !!}</div>
                        <div>有效：{!! $out->expired_date ?? $na !!}</div>
                    </td>
                    <td nowrap>
                        {{ $statuses[$out->status] ?? '' }}

                        @if ($out->status == 40 || $out->status == 60)
                            <br>
                            <button type="button" onclick="show_stock_records({{ $out->id }})" class="btn btn-outline-success btn-sm">
                                庫存紀錄
                            </button>
                        @endif
                    </td>
                    <td title="利潤" nowrap>
                        總成本：${{ number_format($out->total_cost) }}
                        <br>
                        利潤：${{ number_format($out->total_price - $out->total_cost) }}
                        <br>
                        利潤比例：
                        @if ($out->total_cost > 0)
                            {{ number_format(($out->total_price - $out->total_cost) / $out->total_cost * 100, 2) }}%
                        @else
                            0%
                        @endif
                    </td>
                    <td nowrap>
                        應收：${{ number_format($out->total_price, 2) }}
                        <br>
                        實收：${{ number_format($out->total_pay, 2) }}
                        <br>
                        @if ($out->balance <= 0)
                            剩餘：<span class="text-success">收清</span>
                        @else
                            剩餘：<span class="text-danger">${{ number_format($out->balance, 2) }}</span>
                        @endif
                        <br>
                        <span class="text-primary">({{ $out->tax == 1 ? '含稅' : '未稅' }})</span>
                    </td>
                    <td><div class="memo" title="{{ $out->memo }}">{!! nl2br($out->memo) !!}</div></td>
                    <td align="center">
                        {{-- @if (in->status == 40) --}}
                        @if (\App\Model\User::canAdmin('shopping'))
                            <button type="button" style="font-size: .7rem;" onclick="location.href='{{ route('out.edit', $out->id) }}';" class="btn btn-outline-primary btn-sm p-1">
                                修改
                            </button>

                            @if ($out->status != 40)
                                @if ($out->status != 60)
                                    <button type="button" onclick="deleteIn({{ $out->id }});" class="btn btn-outline-danger btn-sm p-1">
                                        刪除
                                    </button>
                                @endif
                            @else
                                <div>
                                    <button type="button" onclick="cancelIn({{ $out->id }});" class="btn btn-outline-danger btn-sm mt-1">
                                        <i class="fas fa-trash-alt"></i> 取消並補回庫存
                                    </button>
                                </div>
                            @endif

                            <form id="delete-form-{{ $out->id }}" action="{{ route('out.destroy', $out) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <input type="hidden" name="referrer" value="{{ URL::current() }}">
                            </form>

                            <form id="cancel-form-{{ $out->id }}" action="{{ route('out.cancel', $out) }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="referrer" value="{{ URL::current() }}">
                            </form>
                        @elseif (\App\Model\User::canView('shopping'))
                            <button type="button" style="font-size: .7rem;" onclick="location.href='{{ route('out.edit', $out->id) }}';" class="btn btn-outline-success btn-sm p-1">
                                查看
                            </button>
                        @endif
                    </td>
                </tr>

            @endforeach
        </tbody>
    </table>

    <br><br>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="z-index: 6500;">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">庫存紀錄</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <iframe id="modal-iframe" frameBorder="0" src="" height="100%" width="100%"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $("#checkAll").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    function show_stock_records(id) {
        $("#modal-iframe").attr("src", "")
        $("#modal-iframe").attr("src", "/selector/out_stock_records/" + id)
        $("#modal-iframe").height('70vh')
        $('#modal').modal('show')
    }

    function pdfsubmit() {

        var chkArray = [];

        $(".print_pdf:checked").each(function () {
            chkArray.push($(this).val());
        });

        var selected;
        selected = chkArray.join(',');

        if (selected == '') {
            swalOption.type = "error"
            swalOption.title = '請至少選擇一筆銷貨單'
            swal.fire(swalOption)

            return false
        }

        openInNewTab("/print/out_details/" + selected)
    }

    function openInNewTab(url) {
        var win = window.open(url, '_blank');
        win.focus();
    }

    function deleteIn(id) {
        if(confirm('確定要刪除嗎？')){
            $('#delete-form-' + id).submit()
        }
    }

    function cancelIn(id) {
        if(confirm('確定要取消並補回庫存嗎？')){
            $('#cancel-form-' + id).submit()
        }
    }

    function listStockRecords(id) {
        $.magnificPopup.open({
            showCloseBtn : false,
            closeOnBgClick: true,
            fixedContentPos: false,
            items: {
                src: "/selector/out_stock_records/" + id,
                type: "iframe"
            }
        })
    }

    $(function () {
        var table = $('#data').DataTable(dtOptions)

        @if(session()->has('error'))
            swalOption.type = "error"
            swalOption.title = '庫存不足，無法存檔'
            swal.fire(swalOption)
        @endif
    })
</script>
@endsection
