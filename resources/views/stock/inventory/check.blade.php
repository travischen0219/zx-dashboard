@extends('b4.app')

@section('title', '庫存盤點 - 盤點')
@section('page-header')
    <i class="fas fa-tasks active-color mr-1"></i> 庫存盤點 - 盤點
@endsection

@section('css')

@endsection

@section('content')

    <button class="btn btn-primary mb-2" onclick="location.href='/stock/inventory'">
        <i class="fas fa-chevron-left"></i>
        返回
    </button>
    <div class="jumbotron p-4">
        <div class="row">
            <div class="col-4">盤點單號：{{ $inventory->code ? 'INV' . $inventory->code : '' }}</div>
            <div class="col-4">倉庫類別：
                @if ($inventory->category_id == 0)
                    全部
                @else
                    [{{ $inventory->category->code ?? '' }}] {{ $inventory->category->name }}
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-4">盤點名稱：{{ $inventory->name }}</div>
            <div class="col-4">日期：{{ $inventory->start_date }} 至 {{ $inventory->end_date }}</div>
        </div>
        <div class="row">
            <div class="col-12">說明：{{ nl2br($inventory->memo) }}</div>
        </div>
    </div>

    <input type="hidden" name="referrer" value="{{ URL::previous() }}">

    <table class="table table-striped table-bordered table-hover" id="data">
        <thead>
            <tr class="bg-success text-white">
                <th>物料編號</th>
                <th>分類</th>
                <th>品名</th>
                <th>單位</th>
                <th>尺寸</th>
                <th>應有庫存</th>
                <th width="300">盤點數量</th>
                <th>異常</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventoryRecords as $inventoryRecord)
                <tr>
                    <td titler="物料編號" class="align-middle">{{ $inventoryRecord->material->fullCode ?? '' }}</td>
                    <td titler="分類" class="align-middle">
                        [{{ $inventoryRecord->material->material_category_name->code ?? '' }}]
                        {{ $inventoryRecord->material->material_category_name->name ?? '' }}
                    </td>
                    <td titler="品名" class="align-middle">{{ $inventoryRecord->material->fullName ?? '' }}</td>
                    <td titler="單位" class="align-middle">{{ $inventoryRecord->material->material_unit_name->name ?? '' }}</td>
                    <td titler="尺寸" class="align-middle">{{ $inventoryRecord->material->size ?? '' }}</td>
                    <td titler="應有庫存" align="right" class="align-middle">
                        {{ number_format($inventoryRecord->original_inventory ?? 0, 2) }}
                        <input type="hidden"
                            name="original_inventory_{{ $inventoryRecord->id }}"
                            id="original_inventory_{{ $inventoryRecord->id }}"
                            value="{{ $inventoryRecord->original_inventory ?? 0}}" />
                    </td>
                    <td titler="盤點數量" class="text-nowrap align-middle">
                        <div id="inventory_{{ $inventoryRecord->id }}">
                            <input type="number"
                                step="0.01"
                                name="physical_inventory_{{ $inventoryRecord->id }}" id="physical_inventory_{{ $inventoryRecord->id }}"
                                size="10"
                                value="{{ $inventoryRecord->physical_inventory }}"
                                class="form-control w-auto d-inline-block align-middle">
                            <button onclick="saveCheck({{ $inventoryRecord->id }})" class="btn btn-primary align-middle ml-1">存檔</button>
                            <button onclick="saveCheckAuto({{ $inventoryRecord->id }})" class="btn btn-success align-middle ml-1">同應有並存檔</button>
                        </div>
                    </td>
                    <td titler="異常" id="result_{{ $inventoryRecord->id }}" align="center" class="align-middle">
                        @if ($inventoryRecord->physical_inventory != null)
                            @if ($inventoryRecord->original_inventory == $inventoryRecord->physical_inventory)
                                <span class="text-success">正確</span>
                            @else
                                <span class="text-danger">
                                    系統{{ $inventoryRecord->original_inventory > $inventoryRecord->physical_inventory ? '多' : '少'}}
                                    {{ number_format(abs($inventoryRecord->physical_inventory -  $inventoryRecord->original_inventory), 2) }}
                                </span>
                            @endif
                        @else
                            <span class="text-warning">未盤點</span>
                        @endif
                    </td>
                </tr>
            @endforeach
    </table>

    <p class="m-5"></p>
@endsection

@section('script')
    <script>
    $(function () {
        var table = $('#data').DataTable(dtOptions)
    })

    function saveCheck(id) {
        const original_inventory = $('#original_inventory_' + id).val()
        const physical_inventory = $('#physical_inventory_' + id).val()

        if (isNaN(physical_inventory) || physical_inventory == '' || physical_inventory < 0) {
            swalOption.type = "error"
            swalOption.title = '存檔失敗';
            swalOption.html = '盤點數量必須是數字 (0或正數)';
            swal.fire(swalOption);
            return false;
        }

        $("#inventory_" + id).busyLoad('show', { spinner: "accordion" });
        $.post(
            "/stock/inventory/record",
            {
                '_token': "{{ csrf_token() }}",
                'id': id,
                'original_inventory': original_inventory,
                'physical_inventory': physical_inventory
            },
            function(response) {
                $('#physical_inventory_' + id).val(response.physical_inventory)

                if (response.diff == 0) {
                    $("#result_" + id).html(`<span class="text-success">正確</span>`)
                } else {
                    $("#result_" + id).html(`
                        <span class="text-danger">
                            系統${response.sign} ${response.diff}
                        </span>
                    `)
                }
                $("#inventory_" + id).busyLoad('hide');
            }
        )
    }

    function saveCheckAuto(id) {
        $('#physical_inventory_' + id).val($('#original_inventory_' + id).val())
        saveCheck(id)
    }
    </script>
@endsection
