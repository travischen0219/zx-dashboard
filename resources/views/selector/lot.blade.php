@extends('b4.selector')

@section('title','選擇批號')


@section('content')
    <style>
        #category, #category option {
            font-family: 'Courier New';
        }
    </style>
    <div class="container-fluid">
        <h2>
            選擇批號
        </h2>
        <hr>

        <table id="data" class="table table-bordered table-striped table-hover mt-3">
            <thead>
                <tr class="bg-primary text-white">
                    <th>操作</th>
                    <th>批號</th>
                    <th>名稱</th>
                    <th>客戶</th>
                    <th>日期</th>
                    <th>狀態</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lots as $lot)
                    <tr>
                        <td title="操作" nowrap>
                            <button type="button"
                                onclick="selectLot(JSON.stringify({{ json_encode($lot, JSON_HEX_QUOT | JSON_HEX_TAG) }}));"
                                class="btn btn-outline-primary">選擇</button>
                        </td>
                        <td title="批號">{{ $lot->code }}</td>
                        <td title="名稱">{{ $lot->name }}</td>
                        <td title="客戶">{{ $lot->customer ? $lot->customer->fullName : '' }}</td>
                        <td title="日期">
                            開始：{{ $lot->start_date }}
                            <br>
                            結束：{{ $lot->end_date }}
                        </td>
                        <td title="狀態">{{ $lot->status }}</td>
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

    function selectLot(str) {
        parent.applyLot(str);
        parent.$.magnificPopup.close();
    }
    </script>
@endsection
