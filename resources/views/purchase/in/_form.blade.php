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
    <label>採購單號：</label>
    @if (\Request::route()->getName() == 'in.create')
        <span class="text-danger">自動產生</span>
    @elseif (\Request::route()->getName() == 'in.edit')
        <span class="text-primary">{{ $in->code }}</span>
    @endif
</div>

<div class="form-group">
    <label for="lot_id"><span class="text-danger">*</span> 批號：</label>
    <button type="button" id="btn_lot_id" class="btn btn-primary" onclick="listLots()">
        @if (old('lot_id') !== null)
            {{ $lots[old('lot_id')]->fullName }}
        @elseif (isset($lots[$in->lot_id]))
            {{ $lots[$in->lot_id]->fullName }}
        @else
            按此選擇批號
        @endif
    </button>

    <input type="hidden" name="lot_id" id="lot_id" value="{{ old('lot_id') ?? $in->lot_id }}">

    <label for="supplier_id"><span class="text-danger">*</span> 供應商：</label>
    <button type="button" id="btn_supplier_id" class="btn btn-primary" onclick="listSuppliers()">
        @if (old('supplier_id') !== null)
            {{ $suppliers[old('supplier_id')]->fullName }}
        @elseif (isset($suppliers[$in->supplier_id]))
            {{ $suppliers[$in->supplier_id]->fullName }}
        @else
            按此選擇供應商
        @endif
    </button>

    <input type="hidden" name="supplier_id" id="supplier_id" value="{{ old('supplier_id') ?? $in->supplier_id }}">
</div>

<div class="form-group">
    <label for="buy_date"><span class="text-danger">*</span> 採購日期：</label>
    <input type="text" name="buy_date" id="buy_date" value="{{ old('buy_date') ?? $in->buy_date }}"
        class="form-control datepicker" placeholder="請輸入採購日期" autocomplete="off" />

    <label for="should_arrive_date">預計到貨：</label>
    <input type="text" name="should_arrive_date" id="should_arrive_date" value="{{ old('should_arrive_date') ?? $in->should_arrive_date }}"
        class="form-control datepicker" placeholder="請輸入預計到貨" autocomplete="off" />

    <label for="arrive_date">實際到貨：</label>
    <input type="text" name="arrive_date" id="arrive_date" value="{{ old('arrive_date') ?? $in->arrive_date }}"
        class="form-control datepicker" placeholder="請輸入實際到貨" autocomplete="off" />
</div>

<div class="form-group">
    <label for="memo" class="align-top">備註：</label>

    <textarea name="memo" id="memo"
        cols="50" rows="5"
        placeholder="請輸入備註"
        class="form-control">{{ old('memo') ?? $in->memo }}</textarea>
</div>

<material-table
    :rows="rows"
    :units="units"
    ref="materialTable">
</material-table>
