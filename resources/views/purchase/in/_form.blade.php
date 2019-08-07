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
        <span class="text-primary">P{{ $in->code }}</span>
    @endif
</div>

<div class="form-group">
    <label for="lot_id"><span class="text-danger">*</span> 批號：</label>
    <button type="button" id="btn_lot_id" class="btn btn-primary" onclick="listLots()">
        @if (old('lot_id'))
            {{ $lots[old('lot_id')]->code }} {{ $lots[old('lot_id')]->name }}
        @elseif (isset($lots[$in->lot_id]))
            {{ $lots[$in->lot_id]->code }} {{ $lots[$in->lot_id]->name }}
        @else
            按此選擇批號
        @endif
    </button>

    <input type="hidden" name="lot_id" id="lot_id" value="{{ old('lot_id') ?? $in->lot_id }}">

    <label for="supplier_id"><span class="text-danger">*</span> 供應商：</label>
    <button type="button" id="btn_supplier_id" class="btn btn-primary" onclick="listSuppliers()">
        @if (old('supplier_id'))
            {{ $suppliers[old('supplier_id')]->code }} {{ $suppliers[old('supplier_id')]->shortName }}
        @elseif (isset($suppliers[$in->supplier_id]))
            {{ $suppliers[$in->supplier_id]->code }} {{ $suppliers[$in->supplier_id]->shortName }}
        @else
            按此選擇供應商
        @endif
    </button>

    <input type="hidden" name="supplier_id" id="supplier_id" value="{{ old('supplier_id') ?? $in->supplier_id }}">

    <label for="manufacturer_id">加工廠商：</label>
    <button type="button" id="btn_manufacturer_id" class="btn btn-primary" onclick="listManufacturers()">
        @if (old('manufacturer_id'))
            {{ $manufacturers[old('manufacturer_id')]->code }} {{ $manufacturers[old('manufacturer_id')]->shortName }}
        @elseif (isset($manufacturers[$in->manufacturer_id]))
            {{ $manufacturers[$in->manufacturer_id]->code }} {{ $manufacturers[$in->manufacturer_id]->shortName }}
        @else
            按此選擇加工廠商
        @endif
    </button>

    <input type="hidden" name="manufacturer_id" id="manufacturer_id" value="{{ old('manufacturer_id') ?? $in->manufacturer_id }}">
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
    <label for="status">狀態：</label>
    @if ($in->status == 40)
        <span class="text-primary">{{ $statuses[$in->status] }}</span>
    @else
        <div class="d-inline-block pl-2">
            <ul class="steps" style="margin: 30px 0;">
                @php
                    $status = old('status') ?? $in->status;
                @endphp
                <li data-status="50" class="btn {{ $status == 50 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[50] }}</li>
                <li data-status="10" class="btn {{ $status == 10 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[10] }}</li>
                <li data-status="20" class="btn {{ $status == 20 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[20] }}</li>
                <li>
                    <ul class="steps-slave">
                        <li data-status="40" class="btn {{ $status == 40 ? 'btn-primary' : 'btn-secondary' }}">
                            {{ $statuses[40] }}
                        </li>
                        <li data-status="30" class="btn {{ $status == 30 ? 'btn-primary' : 'btn-secondary' }}">
                            {{ $statuses[30] }}
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    @endif
    <input type="hidden" name="status" id="status" value="{{ old('status') ?? $in->status }}">
</div>

<div class="form-group">
    <label for="status" class="align-top">狀態說明：</label>
    <div class="d-inline-block text-primary">
        轉入庫之後，狀態跟物料清單將 [ <span class="text-danger">無法再編輯</span> ]
        <br>轉入庫 [ <span class="text-danger">會</span> ] 修改庫存數，轉加工 [ <span class="text-danger">不會</span> ] 修改庫存數
    </div>
</div>

<div class="form-group">
    <label for="memo" class="align-top">備註：</label>

    <textarea name="memo" id="memo"
        cols="50" rows="5"
        placeholder="請輸入備註"
        class="form-control">{{ old('memo') ?? $in->memo }}</textarea>
</div>

<input type="hidden" name="referrer" value="{{ URL::previous() }}">

@if ($in->status == 40)
    <material-view
        :materials="materials"
        :units="units"
        :module="true"
        ref="materialView">
    </material-view>
@else
    <material-table
        :materials="materials"
        :units="units"
        :module="true"
        :total_cost.sync="total_cost"
        ref="materialTable">
    </material-table>
@endif

<pay-table
    :pays="pays"
    :invoice_types="invoice_types"
    :total_cost="total_cost"
    ref="payTable">
</pay-table>
