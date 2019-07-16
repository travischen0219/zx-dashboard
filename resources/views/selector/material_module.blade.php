@extends('b4.selector')

@section('title','選擇物料模組')

@section('content')
    <style>
        #category, #category option {
            font-family: 'Courier New';
        }
    </style>
    <div class="container-fluid">
        <h2>
            選擇物料模組
        </h2>

        <table id="data" class="table table-bordered table-striped table-hover mt-3">
            <thead>
                <tr class="bg-primary text-white">
                    <th nowrap>操作</th>
                    <th nowrap>編號</th>
                    <th nowrap>名稱</th>
                    <th nowrap>說明</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($material_modules as $material_module)
                    <tr>
                        <td title="操作" nowrap>
                            <button type="button"
                                onclick="selectMaterialModule(JSON.stringify({{ json_encode($material_module->material, JSON_HEX_QUOT | JSON_HEX_TAG) }}));"
                                class="btn btn-outline-primary">選擇</button>
                        </td>
                        <td title="編號">{{ $material_module->code }}</td>
                        <td title="名稱">{{ $material_module->name }}</td>
                        <td title="說明">{{ $material_module->memo }}</td>
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

    function selectMaterialModule(str) {
        parent.applyMaterialModule(str);

        parent.$.magnificPopup.close();
    }
    </script>
@endsection
