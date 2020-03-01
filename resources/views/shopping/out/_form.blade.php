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
    <label>銷貨單號：</label>
    @if (\Request::route()->getName() == 'out.create')
        <span class="text-danger">自動產生</span>
    @elseif (\Request::route()->getName() == 'out.edit')
        <span class="text-primary">S{{ $out->code }}</span>
    @endif
</div>

<div class="form-group">
    <label for="lot_id">批號：</label>
    <button type="button" id="btn_lot_id" class="btn btn-primary" onclick="listLots()">
        @if (old('lot_id'))
            {{ $lots[old('lot_id')]->code }} {{ $lots[old('lot_id')]->name }}
        @elseif (isset($lots[$out->lot_id]))
            {{ $lots[$out->lot_id]->code }} {{ $lots[$out->lot_id]->name }}
        @else
            按此選擇批號
        @endif
    </button>

    <input type="hidden" name="lot_id" id="lot_id" value="{{ old('lot_id') ?? $out->lot_id }}">

    <label for="customer_id"><span class="text-danger">*</span> 客戶：</label>
    <button type="button" id="btn_customer_id" class="btn btn-primary" onclick="listCustomers()">
        @if (old('customer_id'))
            {{ $customers[old('customer_id')]->code }} {{ $customers[old('customer_id')]->shortName }}
        @elseif (isset($customers[$out->customer_id]))
            {{ $customers[$out->customer_id]->code }} {{ $customers[$out->customer_id]->shortName }}
        @else
            按此選擇客戶
        @endif
    </button>

    <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') ?? $out->customer_id }}">
</div>

<div class="form-group">
    <label for="created_date"><span class="text-danger">*</span> 新增日期：</label>
    <input type="text" name="created_date" id="created_date" value="{{ old('created_date') ?? $out->created_date }}"
        class="form-control datepicker" placeholder="請輸入新增日期" autocomplete="off" />

    <label for="expired_date">有效期限：</label>
    <input type="text" name="expired_date" id="expired_date" value="{{ old('expired_date') ?? $out->expired_date }}"
        class="form-control datepicker" placeholder="請輸入有效期限" autocomplete="off" />
</div>

<div class="form-group">
    <label for="status">狀態：</label>
    @if ($out->status == 40 || $out->status == 60)
        <span class="text-primary">{{ $statuses[$out->status] }}</span>
    @else
        <div class="d-inline-block pl-2">
            <ul class="steps" style="margin: 30px 0;">
                @php
                    $status = old('status') ?? $out->status;
                @endphp
                <li data-status="50" class="btn {{ $status == 50 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[50] }}</li>
                <li data-status="10" class="btn {{ $status == 10 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[10] }}</li>
                <li data-status="20" class="btn {{ $status == 20 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[20] }}</li>
                <li data-status="30" class="btn {{ $status == 30 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[30] }}</li>
                <li data-status="35" class="btn {{ $status == 35 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[35] }}</li>
                <li data-status="40" class="btn {{ $status == 40 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[40] }}</li>
                <li data-status="60" class="btn {{ $status == 60 ? 'btn-primary' : 'btn-secondary' }}">{{ $statuses[60] }}</li>
            </ul>
        </div>
    @endif
    <input type="hidden" name="status" id="status" value="{{ old('status') ?? $out->status }}">
</div>

<div class="form-group">
    <label for="status" class="align-top">狀態說明：</label>
    <div class="d-inline-block text-primary">
        出庫之後，狀態跟物料清單將 [ <span class="text-danger">無法再編輯</span> ]
        <br>轉出庫 [ <span class="text-danger">會</span> ] 修改庫存數
    </div>
</div>

{{-- <div class="form-group">
    <label for="sale_cost">管銷費用：</label>
    <input type="number" name="sale_cost" id="sale_cost" value="{{ old('sale_cost') ?? $out->sale_cost }}"
        class="form-control" placeholder="請輸入比率" autocomplete="off" size="10" /> %
</div> --}}

<div class="form-group d-inline-block">
    <label for="tax1">稅率：</label>
    <div class="custom-control custom-radio d-inline-block">
        <input class="custom-control-input" type="radio" name="tax" id="tax1" value="1" {{ $out->tax == 1 ? 'checked' : '' }}>
        <label class="custom-control-label text-left text-primary" for="tax1">含稅</label>
    </div>
    <div class="custom-control custom-radio d-inline-block">
        <input class="custom-control-input" type="radio" name="tax" id="tax0" value="0" {{ $out->tax == 0 ? 'checked' : '' }}>
        <label class="custom-control-label text-left text-danger" id="red-label" for="tax0">未稅</label>
    </div>
</div>

<div class="form-group">
    <label for="status" class="align-top">稅率說明：</label>
    <div class="d-inline-block text-danger">
        資料存檔後，收款總金額才會更新
    </div>
</div>

<div class="form-group">
    <label for="memo" class="align-top">備註：</label>

    <textarea name="memo" id="memo"
        cols="50" rows="5"
        placeholder="請輸入備註"
        class="form-control">{{ old('memo') ?? $out->memo }}</textarea>
</div>

<input type="hidden" name="referrer" value="{{ URL::previous() }}">
<input type="hidden" name="total_cost" v-model="total_cost">
<input type="hidden" name="total_price" v-model="total_price">

@if ($out->status == 40 || $out->status == 60)
    <material-module-view
        :material_modules="material_modules"
        :total_cost.sync="total_cost"
        :total_price.sync="total_price"
        ref="materialModuleView">
    </material-module-view>
@else
    <material-module-table
        :material_modules="material_modules"
        :total_cost.sync="total_cost"
        :total_price.sync="total_price"
        :update="true"
        ref="materialModuleView">
    </material-module-table>
@endif

<pay-table
    :way="2"
    :title="'收'"
    :pays="pays"
    :tax="tax"
    :invoice_types="invoice_types"
    :total_cost="total_price"
    ref="payTable">
</pay-table>
