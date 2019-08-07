@extends('b4.app')

@section('title', '庫存盤點 - 盤點')
@section('page-header')
    <i class="fas fa-tasks active-color mr-1"></i> 庫存盤點 - 盤點
@endsection

@section('css')

@endsection

@section('content')

    <button class="btn btn-primary mb-2" onclick="location.href='/stock/inventory/{{ $inventory->id }}/view'">
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

        <hr />

        <div class="form-group">
            [{{ $inventoryRecord->material->material_category_name->code ?? '' }}]
            {{ $inventoryRecord->material->material_category_name->name ?? '' }}
            ：
            {{ $inventoryRecord->material->fullCode }}
            {{ $inventoryRecord->material->fullName }}
        </div>

        <div class="form-group">
            <label class="w-auto">應有庫存：</label>
            @if ($inventoryRecord->original_inventory != null)
                {{ number_format($inventoryRecord->original_inventory ?? 0, 2) }}
            @else
                {{ number_format($inventoryRecord->material->stock ?? 0, 2) }}
            @endif

            <label class="w-auto ml-3">盤點數量：</label>
            @if ($inventoryRecord->physical_inventory != null)
                {{ number_format($inventoryRecord->physical_inventory, 2) }}
            @endif
        </div>
        <div class="form-group">
            <label class="w-auto">異常：</label>
            <span class="text-warning">
                {{ number_format($inventoryRecord->physical_inventory -  $inventoryRecord->original_inventory, 2) }}
            </span>

            <label class="w-auto ml-3">調整：</label>
            <span class="text-info">
                {{ number_format($inventoryRecord->fix(), 2) }}
            </span>

            <label class="w-auto ml-3">差異剩餘：</label>
            <span class="text-danger">
                {{ number_format($inventoryRecord->least(), 2) }}
            </span>
        </div>
    </div>

    <input type="hidden" name="referrer" value="{{ URL::previous() }}">

    <fieldset>
        <legend>新增差異</legend>
        <form action="/stock/inventory/fixSave" method="post" class="form">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="amount" class="w-auto">數量：</label>
                <input type="number" step="0.01" class="form-control w-auto d-inline-block" name="amount" id="amount" aria-describedby="helpId" placeholder="請輸入數字">
                <label for="memo" class="w-auto ml-3">說明：</label>
                <input type="text" class="form-control w-25" name="memo" id="memo">
                <button type="submit" class="btn btn-primary align-top ml-2">保存</button>
                <small id="helpId" class="form-text text-danger">請輸入正負數字調整差異，例如：100 或 -100</small>
            </div>

            <input type="hidden" name="id" id="id" value="{{ $inventoryRecord->id }}">
        </form>
    </fieldset>

    <fieldset>
        <legend>差異處理紀錄</legend>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr class="bg-success text-white">
                    <th>處理時間</th>
                    <th>數量</th>
                    <th>入庫 (前→後) 數量</th>
                    <th>說明</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                    <tr>
                        <td>{{ $stock->created_at }}</td>
                        <td>{{ $stock->amount }}</td>
                        <td title="入庫 (前→後) 數量">{{ $stock->amount_before }} → {{ $stock->amount_after }}</td>
                        <td>{{ $stock->memo }}</td>
                    </tr>
                @endforeach
        </table>
    </fieldset>

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
