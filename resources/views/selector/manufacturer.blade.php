@extends('b4.selector')

@section('title','選擇加工廠商')


@section('content')
    <style>
        #category, #category option {
            font-family: 'Courier New';
        }
    </style>
    <div class="container-fluid">
        <h2>
            選擇加工廠商
        </h2>
        <hr>
        <label>
            篩選分類：
            <select
                name="category"
                id="category"
                class="form-control d-inline-block w-auto"
                onchange="location.href='/selector/manufacturer/' + this.value">
                <option value="">全部</option>
                @foreach ($categories as $key => $value)
                    <option value="{{ $key }}" {{ $key == $category ? 'selected': '' }}>{{ $value }}</option>
                @endforeach
            </select>
        </label>

        <table id="data" class="table table-bordered table-striped table-hover mt-3">
            <thead>
                <tr class="bg-primary text-white">
                    <th>操 作</th>
                    <th>編 號</th>
                    <th>分 類</th>
                    <th>全 名</th>
                    <th>電 話</th>
                    <th>地 址</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($manufacturers as $manufacturer)
                    <tr>
                        <td title="操作" nowrap>
                            <button type="button"
                                onclick="selectManufacturer(JSON.stringify({{ json_encode($manufacturer, JSON_HEX_QUOT | JSON_HEX_TAG) }}));"
                                class="btn btn-outline-primary">選擇</button>
                        </td>
                        <td title="編號">{{ $manufacturer->code }}</td>
                        <td title="分類">
                            {{ $categories[$manufacturer->category] ?? '' }}
                        </td>
                        <td title="全名">{{ $manufacturer->fullName }}</td>
                        <td title="電話">{{ $manufacturer->tel }}</td>
                        <td title="地址">{{ $manufacturer->address }}</td>
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

    function selectManufacturer(str) {
        parent.applyManufacturer(str);
        parent.$.magnificPopup.close();
    }
    </script>
@endsection
