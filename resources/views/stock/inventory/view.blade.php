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
                <th>分類</th>
                <th>品名</th>
                <th>單位</th>
                <th>尺寸</th>
                <th>應有庫存</th>
                {{-- <th>目前庫存</th> --}}
                <th>盤點數量</th>
                <th>異常</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventoryRecords as $inventoryRecord)
                <tr>
                    <td titler="分類" class="align-middle">
                        [{{ $inventoryRecord->material->material_category_name->code ?? '' }}]
                        {{ $inventoryRecord->material->material_category_name->name ?? '' }}
                    </td>
                    <td titler="品名" class="align-middle">
                        {{ $inventoryRecord->material->fullCode ?? '' }}<br>
                        {{ $inventoryRecord->material->fullName ?? '' }}
                    </td>
                    <td titler="單位" class="align-middle">{{ $inventoryRecord->material->material_unit_name->name ?? '' }}</td>
                    <td titler="尺寸" class="align-middle">{{ $inventoryRecord->material->size ?? '' }}</td>
                    <td titler="應有庫存" align="right" class="align-middle">
                        @if ($inventoryRecord->original_inventory != null)
                            {{ number_format($inventoryRecord->original_inventory ?? 0, 2) }}
                        @else
                            {{ number_format($inventoryRecord->material->stock ?? 0, 2) }}
                        @endif
                    </td>
                    {{-- <td titler="目前庫存" align="right" class="align-middle">
                        {{ number_format($inventoryRecord->material->stock ?? 0, 2) }}
                    </td> --}}
                    <td titler="盤點數量" align="right" class="text-nowrap align-middle">
                        @if ($inventoryRecord->physical_inventory != null)
                            {{ number_format($inventoryRecord->physical_inventory, 2) }}
                        @endif
                    </td>
                    <td titler="異常" id="result_{{ $inventoryRecord->id }}" align="left" class="align-middle" nowrap>
                        @if ($inventoryRecord->physical_inventory != null)
                            @if ($inventoryRecord->physical_inventory -  $inventoryRecord->original_inventory == 0)
                                <span class="text-success">正確</span>
                            @else
                                <div>
                                    異常：<span class="text-warning">
                                        {{ number_format($inventoryRecord->physical_inventory -  $inventoryRecord->original_inventory, 2) }}
                                    </span>
                                </div>
                                <div>
                                    調整：<span class="text-info">
                                        {{ number_format($inventoryRecord->fix(), 2) }}
                                    </span>
                                </div>
                                <div>
                                    差異剩餘：<span class="text-danger">
                                        {{ number_format($inventoryRecord->physical_inventory -  $inventoryRecord->original_inventory - $inventoryRecord->fix(), 2) }}
                                    </span>
                                </div>
                            @endif
                        @else
                            <span class="text-warning">未盤點</span>
                        @endif
                    </td>
                    <td title="操作" align="center" class="align-middle" nowrap>
                        @if ($inventoryRecord->physical_inventory != null)
                            @if ($inventoryRecord->original_inventory != $inventoryRecord->physical_inventory)
                                @if ($inventoryRecord->least() != 0)
                                    <button type="button"
                                        class="btn btn-sm btn-warning"
                                        onclick="quickFix({{ $inventoryRecord->id }});">
                                        <i class="fas fa-bolt"></i> 快速修正
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-info"
                                        onclick="fix({{ $inventoryRecord->id }});">
                                        <i class="fas fa-balance-scale-right"></i> 差異處理
                                    </button>
                                @else
                                    <button type="button"
                                        class="btn btn-sm btn-outline-success"
                                        disabled="disabled">
                                        <i class="fas fa-check"></i> 已修正
                                    </button>
                                @endif
                            @endif
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

        $('#data').on('page.dt', function () {
            var info = table.page.info()
            console.log(info)
        })
    })

    function saveCheck(id) {
        const original_inventory = $('#original_inventory_' + id).val()
        const physical_inventory = $('#physical_inventory_' + id).val()

        if (isNaN(physical_inventory) || physical_inventory == '') {
            swalOption.type = "error"
            swalOption.title = '存檔失敗';
            swalOption.html = '盤點數量必須是數字';
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
                location.reload()
                // $('#physical_inventory_' + id).val(response.physical_inventory)

                // if (response.diff == 0) {
                //     $("#result_" + id).html(`<span class="text-success">正確</span>`)
                // } else {
                //     $("#result_" + id).html(`
                //         <span class="text-danger">
                //             ${response.diff}
                //         </span>
                //     `)
                // }
                // $("#inventory_" + id).busyLoad('hide');
            }
        )
    }

    function saveCheckAuto(id) {
        $('#physical_inventory_' + id).val($('#original_inventory_' + id).val())
        saveCheck(id)
    }

    function quickFix(id) {
        swal.fire({
            title: '快速修正',
            html: '將會依照差異數量自動填入誤差處理中，是否繼續？',
            type: 'info',
            showCancelButton: true,
            confirmButtonText: '確定修正',
            cancelButtonText: '取消',
            width: 600
        }).then((result) => {
            if (result.value) {
                $.busyLoadFull("show", {
                    textPosition: "bottom",
                    textMargin: "20px",
                    background: "rgba(0, 0, 0, 0.70)",
                    text: '資料送出中，請勿關閉或離開...'
                })

                location.href = `/stock/inventory/${id}/quickFix`
            } else {
                return false
            }
        })
        // swal({
        //     title: "快速修正",
        //     text: "將會依照差異數量自動填入誤差處理中，是否繼續？",
        //     type: "warning",
        //     showCancelButton: true,
        //     confirmButtonColor: "#DD6B55",
        //     confirmButtonText: '確定',
        //     cancelButtonText: '取消',
        //     closeOnConfirm: false
        // }, function () {
        //     location.href = '/stock/inventory/quick_fix/{{ $inventory->id }}/' + id;
        // })
    }

    function fix(id) {
        location.href = `/stock/inventory/${id}/fix`
    }
    </script>
@endsection
