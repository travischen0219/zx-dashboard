@extends('b4.app')

@section('title','銷貨通知')
@section('page-header')
    <i class="fas fa-shopping-cart active-color mr-2"></i>採購進貨 - 銷貨通知
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
    @include('includes.messages')

    <table class="table table-striped table-bordered table-hover" id="data" style="font-size: .8rem;">
        <thead>
            <tr>
                <th width="50">讀取</th>
                <th>銷貨單</th>
                <th>狀態</th>
                <th>通知時間</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifies as $notify)
                <tr>
                    <td>{!! $notify->view ? '已讀' : '<span class="text-danger">未讀</span>' !!}</td>
                    <td>
                        {!! $notify->out->lot ? $notify->out->lot->code . '<br>' . $notify->out->lot->name : $na !!}
                        @if ($notify->out->project != '')
                            <div>工：{{ $notify->out->project }}</div>
                        @endif
                    </td>
                    <td>{{ $statuses[$notify->status] }}</td>
                    <td>{{ $notify->updated_at }}</td>
                    <td>
                        <button class="btn btn-primary" onclick="view({{ $notify->id }})">查看</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('script')
<script>
    $(function () {
        var table = $('#data').DataTable(dtOptions)
    })

    function view(id) {
        location.href = '/purchase/notify/' + id
    }
</script>
@endsection
