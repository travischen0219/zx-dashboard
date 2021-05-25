@extends('b4.selector')

@section('title','選擇物料')

@section('content')
    <style>
        #category, #category option {
            font-family: 'Courier New';
        }
    </style>
    <div class="container-fluid">
        <h2>
            選擇物料
            <small class="text-danger">尚未指定 單位 之物料不顯示</small>
        </h2>
        <hr>
        <label>
            篩選分類：
            <select
                name="category"
                id="category"
                class="form-control d-inline-block w-auto"
                onchange="location.href='/selector/material/{{ $idx }}/' + this.value">
                <option value="">全部</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->code }}" {{ $code == $category->code ? 'selected': '' }}>[{{ $category->code }}] {{ $category->name }}</option>
                @endforeach
            </select>
        </label>

        <table id="data" class="table table-bordered table-striped table-hover mt-3">
            <thead>
                <tr class="bg-primary text-white">
                    <th nowrap>操作</th>
                    <th nowrap>分類</th>
                    <th nowrap>編號</th>
                    <th nowrap>品名</th>
                    <th nowrap>單位</th>
                    <th nowrap>尺寸</th>
                    <th nowrap>成本</th>
                    <th nowrap>顏色</th>
                    <th nowrap>庫存</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($materials as $material)
                    @php
                        $material->cal = $categories[$material->material_categories_code]->cal;
                    @endphp
                    <tr>
                        <td title="操作" nowrap>
                            <button type="button"
                                data-id="{{ $material->id }}"
                                onclick="selectMaterial(JSON.stringify({{ json_encode($material, JSON_HEX_QUOT | JSON_HEX_TAG) }}), {{ $idx }});"
                                class="btn btn-outline-primary btn-select">
                                選擇
                            </button>
                        </td>
                        <td title="分類">
                            [{{ $material->material_categories_code }}]
                            {{ $categories[$material->material_categories_code]->name }}
                        </td>
                        <td title="編號">{{ $material->fullCode }}</td>
                        <td title="品名">{{ $material->fullName }}</td>
                        <td title="單位" align="center">{{ $units[$material->unit]->name }}</td>
                        <td title="尺寸">{{ $material->size }}</td>
                        <td title="成本" align="right">{{ $material->cost }}</td>
                        <td title="顏色">{{ $material->color }}</td>
                        <td title="庫存" align="right">{{ number_format($material->stock, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
    var stock = 0
    $(function() {
        if (parent.app.stock == 1) {
            stock = 1
        }

        $('#data').DataTable({
            "language": {
                "url": '/json/datatable.zh-tw.json'
            }
        });

        // 更新選擇狀態
        var selected = []

        if (stock == 1) {
            selected = parent.app.stocks.map((value, index, array) => {
                return value.id
            })
        } else {
            selected = parent.app.materials.map((value, index, array) => {
                return value.id
            })
        }

        $('.btn-select').each(function (index) {
            if (selected.indexOf($(this).data('id')) >= 0 || selected.indexOf($(this).data('id').toString()) >= 0) {
                $(this)
                    .html('已選')
                    .removeClass('btn-outline-primary')
                    .prop('disabled', true)
            }
        })
    })

    function selectMaterial(str, idx) {
        // console.log(str, idx)

        parent.applyMaterial(str, idx)

        // 更新選擇狀態
        var selected = []
        if (stock == 1) {
            selected = parent.app.stocks.map((value, index, array) => {
                return value.id
            })
        } else {
            selected = parent.app.materials.map((value, index, array) => {
                return value.id
            })
        }

        $('.btn-select').each(function (index) {
            if (selected.indexOf($(this).data('id')) >= 0 || selected.indexOf($(this).data('id').toString()) >= 0) {
                $(this)
                    .html('已選')
                    .removeClass('btn-outline-primary')
                    .prop('disabled', true)
            }
        })

        // parent.$.magnificPopup.close();
    }
    </script>
@endsection
