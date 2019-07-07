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
    <label for="code"><span class="text-danger">*</span> 批號：</label>
    @if (\Request::route()->getName() == 'lot.create')
        <input type="text" name="code" id="code" value="{{ old('code') ?? $lot->code }}"
            class="form-control" placeholder="請輸入批號" />
    @elseif (\Request::route()->getName() == 'lot.edit')
        {{ $lot->code }} <span class="text-danger">不可修改</span>
    @endif
</div>

<div class="form-group">
    <label for="code"><span class="text-danger">*</span> 案件名稱：</label>
    <input type="text" name="name" id="name" value="{{ old('name') ?? $lot->name }}"
        class="form-control" placeholder="請輸入案件名稱" />
</div>

<div class="form-group">
    <label for="customer_id"><span class="text-danger">*</span> 客戶：</label>
    <button type="button" id="btn_customer_id" class="btn btn-primary" onclick="listCustomers()">
        @if (old('customer_id') !== null)
            {{ $customers[old('customer_id')]->fullName }}
        @elseif (isset($customers[$lot->customer_id]))
            {{ $customers[$lot->customer_id]->fullName }}
        @else
            按此選擇客戶
        @endif
    </button>

    <input type="hidden" name="customer_id" id="customer_id" value="{{ old('customer_id') ?? $lot->customer_id }}">
</div>

<div class="form-group">
    <label for="start_date"><span class="text-danger">*</span> 日期：</label>
    <input type="text" name="start_date" id="start_date" value="{{ old('start_date') ?? $lot->start_date }}"
        class="form-control" placeholder="請輸入開始日期" autocomplete="off" />

    <label for="end_date" class="w-auto mx-2">至</label>
    <input type="text" name="end_date" id="end_date" value="{{ old('end_date') ?? $lot->end_date }}"
        class="form-control" placeholder="請輸入結束日期" autocomplete="off" />
</div>

<div class="form-group">
    <label for="status">案件狀態：</label>

    <input type="text" name="status" id="status" value="{{ old('status') ?? $lot->status }}"
        class="form-control" placeholder="請輸入案件狀態" />

    <span class="text-warning ml-2">此欄位連動報價單</span>
</div>

<div class="form-group">
    <label>是否完工：</label>

    <input type="checkbox" name="is_finished" id="is_finished" value="1"
        {{ (old('is_finished') ?? $lot->is_finished) == 1 ? 'checked' : '' }} />

    <label for="is_finished" class="w-auto text-primary">已經完工取勾選此方塊</label>
</div>

<div class="form-group">
    <label for="memo" class="align-top">備註：</label>

    <textarea name="memo" id="memo"
        cols="50" rows="5"
        placeholder="請輸入備註"
        class="form-control">{{ old('memo') ?? $lot->memo }}</textarea>
</div>
