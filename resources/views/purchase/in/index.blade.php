@extends('b4.app')

@section('title','採購')
@section('page-header')
    <i class="fas fa-shopping-cart active-color mr-2"></i>採購進貨 - 採購
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
    @php
        $na = '<span class="text-muted">未選</span>';
    @endphp

    <div class="form-group">
        <label class="control-label">狀態：</label>
        <select class="form-control d-inline-block w-auto" name="status" onchange="location.href='/purchase/in/search/' + this.value + '/{{ $pay_status }}'">
            <option value="0">全部</option>
            @foreach ($statuses as $key => $value)
                <option value="{{ $key }}" {{ $key == $status ? 'selected' : ''}}>{{ $value }}</option>
            @endforeach
        </select>

        <label class="control-label ml-3">付款：</label>
        <select class="form-control d-inline-block w-auto" name="status" onchange="location.href='/purchase/in/search/{{ $status }}/' + this.value">
            <option value="0">全部</option>
            @foreach ($pay_statuses as $key => $value)
                <option value="{{ $key }}" {{ $key == $pay_status ? 'selected' : ''}}>{{ $value }}</option>
            @endforeach
        </select>
    </div>

    <hr>

    @include('includes.messages')

    <a href="{{ route('in.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增採購</a>
    <span class="btn btn-primary mr-2" onclick="pdfsubmit();"><i class="fa fa-print"></i> 多筆列印</span>
    <label for="checkAll">全選</label> <input type="checkbox" class="checkAll" id="checkAll" value="1">

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
                        <a href="/print/in_detail/{{ $in->id }}" target="_blank"
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
                    <td>
                        {{ $statuses[$in->status] ?? '' }}

                        @if ($in->status == 40)
                            <br>
                            <button type="button" onclick="show_stock_records({{ $in->id }})" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-eye"></i> 庫存紀錄
                            </button>
                        @endif
                    </td>
                    <td>
                        應付：${{ number_format($in->total_cost) }}
                        <br>
                        實付：${{ number_format($in->total_pay) }}
                        <br>
                        @if ($in->balance <= 0)
                            剩餘：<span class="text-success">付清</span>
                        @else
                            剩餘：<span class="text-danger">${{ number_format($in->balance) }}</span>
                        @endif
                    </td>
                    <td><div class="memo" title="{{ $in->memo }}">{{ $in->memo }}</div></td>
                    <td align="center">
                        {{-- @if (in->status == 40) --}}
                        @if (false)
                            <button type="button" onclick="listStockRecords({{ $in->id }})" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-eye"></i> 查看
                            </button>
                        @else
                            <button type="button" onclick="location.href='{{ route('in.edit', $in->id) }}';" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-pen"></i> 修改
                            </button>

                            @if ($in->status != 40)
                                <button type="button" onclick="deleteIn({{ $in->id }});" class="btn btn-outline-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> 刪除
                                </button>
                            @endif

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
        $("#modal-iframe").attr("src", "/selector/in_stock_records/" + id)
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
            swalOption.title = '請至少選擇一筆採購單'
            swal.fire(swalOption)

            return false
        }

        openInNewTab("/print/in_details/" + selected)
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

    function listStockRecords(id) {
        $.magnificPopup.open({
            showCloseBtn : false,
            closeOnBgClick: true,
            fixedContentPos: false,
            items: {
                src: "/selector/in_stock_records/" + id,
                type: "iframe"
            }
        })
    }

    $(function () {
        var table = $('#data').DataTable(dtOptions)
    })
</script>
@endsection
