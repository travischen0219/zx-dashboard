@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <label class="control-label">類別：</label>
    <select class="form-control d-inline-block w-auto" name="type">
        @foreach ($types as $key => $value)
            @if (in_array($key, [1, 3, 15]))
                <option value="{{ $key }}">{{ $value }}</option>
            @endif
        @endforeach
    </select>

    <label for="buy_date"><span class="text-danger">*</span> 入庫日期：</label>
    <input type="text" name="stock_date" id="stock_date" value="{{ date('Y/m/d') }}"
        class="form-control datepicker" readonly="readonly" placeholder="請輸入入庫日期" autocomplete="off" />
</div>

<div class="form-group">
    <label for="lot_id">批號：</label>
    <button type="button" id="btn_lot_id" class="btn btn-primary" onclick="listLots()">
        按此選擇批號
    </button>

    <input type="hidden" name="lot_id" id="lot_id" value="0">

    @if ($way == 1)
        <label for="supplier_id">供應商：</label>
        <button type="button" id="btn_supplier_id" class="btn btn-primary" onclick="listSuppliers()">
            按此選擇供應商
        </button>

        <input type="hidden" name="supplier_id" id="supplier_id" value="0">
    @elseif ($way == 2)
        <label for="customer_id">客戶：</label>
        <button type="button" id="btn_customer_id" class="btn btn-primary" onclick="listCustomers()">
            按此選擇客戶
        </button>

        <input type="hidden" name="customer_id" id="customer_id" value="0">
    @endif
</div>

<input type="hidden" name="way" value="{{ $way }}">
<input type="hidden" name="referrer" value="{{ URL::previous() }}">

<br>

<stock-table
    :stocks="stocks"
    :units="units"
    ref="stockTable">
</stock-table>
