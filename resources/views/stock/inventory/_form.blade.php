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
    <label for="">盤點單號：</label>
    {{ $inventory->code ? 'INV' . $inventory->code : '自動產生' }}
</div>

@if ($inventory->id > 0)
    <div class="form-group">
        <label class="control-label">倉庫類別：</label>
        @if ($inventory->category_id == 0)
            全部
        @else
            {{ $categories[$inventory->category_id]->name }}
        @endif

        <small class="text-danger ml-2">(盤點中無法修改類別)</small>
        <input type="hidden" name="category_id" value="{{ $inventory->category_id }}">
    </div>
@else
    <div class="form-group">
        <label class="control-label">倉庫類別：</label>
        <select class="form-control d-inline-block w-auto" name="category_id">
            <option value="0">全部</option>
            @foreach ($categories as $key => $value)
                <option value="{{ $key }}" {{ $key == old('category_id') || $key == $inventory->category_id ? 'selected' : '' }}>[{{ $value->code }}] {{ $value->name }}</option>
            @endforeach
        </select>
    </div>
@endif

<div class="form-group">
    <label for="name"><span class="text-danger">*</span> 盤點名稱：</label>
    <input type="text" name="name" id="name" value="{{ old('name') ?? $inventory->name }}"
        class="form-control" placeholder="請輸入盤點名稱" />
</div>

@if ($inventory->id > 0)
    <div class="form-group">
        <label class="control-label">盤點狀態：</label>
        <select class="form-control d-inline-block w-auto" name="status">
            @foreach ($statuses as $key => $value)
                <option value="{{ $key }}" {{ $key == $inventory->status ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
    </div>
@endif

<div class="form-group">
    <label for="start_date"><span class="text-danger">*</span> 日期：</label>
    <input type="text" name="start_date" id="start_date" value="{{ old('start_date') ?? $inventory->start_date }}"
        class="form-control" readonly="readonly" placeholder="請輸入開始日期" autocomplete="off" />

    <label for="end_date" class="w-auto mx-2">至</label>
    <input type="text" name="end_date" id="end_date" value="{{ old('end_date') ?? $inventory->end_date }}"
        class="form-control" readonly="readonly" placeholder="請輸入結束日期" autocomplete="off" />
</div>

<div class="form-group">
    <label for="memo" class="align-top">說明：</label>

    <textarea name="memo" id="memo"
        cols="50" rows="5"
        placeholder="請輸入備註"
        class="form-control">{{ old('memo') ?? $inventory->memo }}</textarea>
</div>

<input type="hidden" name="referrer" value="{{ URL::previous() }}">
