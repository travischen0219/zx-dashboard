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
                系統{{ $inventoryRecord->original_inventory > $inventoryRecord->physical_inventory ? '多' : '少'}}
                {{ number_format(abs($inventoryRecord->physical_inventory -  $inventoryRecord->original_inventory), 2) }}
            </span>

            <label class="w-auto ml-3">調整：</label>
            <span class="text-info">
                @if ($inventoryRecord->fix() != 0)
                    {{ $inventoryRecord->fix() > 0 ? '+' : '' }}{{ number_format($inventoryRecord->fix(), 2) }}
                @else
                    無
                @endif
            </span>

            <label class="w-auto ml-3">差異剩餘：</label>
            <span class="text-danger">
                @if ($inventoryRecord->original_inventory - $inventoryRecord->physical_inventory + $inventoryRecord->fix() == 0)
                    無
                @else
                    系統{{ $inventoryRecord->original_inventory - $inventoryRecord->physical_inventory + $inventoryRecord->fix() > 0 ? '多' : '少'}}
                    {{ number_format(abs($inventoryRecord->original_inventory - $inventoryRecord->physical_inventory + $inventoryRecord->fix()), 2) }}
                @endif
            </span>
        </div>
    </div>

    <input type="hidden" name="referrer" value="{{ URL::previous() }}">

    <fieldset>
        <legend>新增差異</legend>
        <form action="/stock/inventory/fixSave" method="post" class="form" id="form">
            {{ csrf_field() }}
            <div class="form-group">
                <select name="way" id="way" class="form-control w-auto d-inline-block">
                    <option value="">請選擇入出庫</option>
                    <option value="1">入庫</option>
                    <option value="2">出庫</option>
                </select>
                <label for="amount" class="w-auto ml-2">數量：</label>
                <input type="number" step="0.01" class="form-control w-auto d-inline-block" name="amount" id="amount" aria-describedby="helpId" placeholder="請輸入數字">
                <label for="memo" class="w-auto ml-3">說明：</label>
                <input type="text" class="form-control w-25" name="memo" id="memo">
                <button type="button" onclick="saveCheck()" class="btn btn-primary align-top ml-2">保存</button>
                <small id="helpId" class="form-text text-danger">請輸入正的數字，入庫會增加系統庫存數量，出庫會減少系統庫存數量</small>
            </div>

            <input type="hidden" name="id" id="id" value="{{ $inventoryRecord->id }}">
        </form>
    </fieldset>

    <fieldset>
        <legend>差異處理紀錄</legend>
        <table class="table table-bordered">
            <thead>
                <tr class="bg-success text-white">
                    <th>處理時間</th>
                    <th>入出庫</th>
                    <th>數量</th>
                    <th>入庫 (前→後) 數量</th>
                    <th>說明</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stocks as $stock)
                    @php
                        $bg = '';
                        $rotate = '';
                        if ($stock->way == 1) {
                            $bg = 'table-success';
                            $rotate = 'rotate-up';
                        }
                        if ($stock->way == 2) {
                            $bg = 'table-danger';
                            $rotate = 'rotate-down';
                        }
                    @endphp
                    <tr class="{{ $bg }}">
                        <td>{{ $stock->created_at }}</td>
                        <td>{{ $ways[$stock->way] ?? '' }}</td>
                        <td>{{ $stock->amount }}</td>
                        <td title="入庫 (前→後) 數量">{{ $stock->amount_before }} <span class="{{ $rotate }}">→</span> {{ $stock->amount_after }}</td>
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

    function saveCheck() {
        const way = $('#way').val()
        const amount = $('#amount').val()

        if (way != 1 && way != 2) {
            swalOption.type = "error"
            swalOption.title = '存檔失敗';
            swalOption.html = '請選擇入出庫';
            swal.fire(swalOption);
            return false;
        }

        if (isNaN(amount) || amount == '' || amount < 0) {
            swalOption.type = "error"
            swalOption.title = '存檔失敗';
            swalOption.html = '盤點數量必須是正的數字';
            swal.fire(swalOption);
            return false;
        }

        $('#form').submit()
    }
    </script>
@endsection
