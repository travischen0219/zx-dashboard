@include('b4.error')

<div class="form-group">
    <label for="code"><span class="text-danger">*</span> 編號：</label>
    {{ $material_module->code ?? '自動產生' }}
</div>

<div class="form-group">
    <label for="name"><span class="text-danger">*</span> 名稱：</label>
    <input type="text" name="name" id="name" value="{{ old('name') ?? $material_module->name }}"
        class="form-control" size="40" placeholder="請輸入名稱" />
</div>

<div class="form-group">
    <label for="unit"><span class="text-danger">*</span>單位：</label>
    <select class="form-control d-inline-block" id="unit" name="unit" style="width: auto;">
        <option value="0">請選擇</option>
        @foreach($material_units as $unit)
            <option value="{{ $unit->id }}" {{ (old('unit') ?? $material_module->unit) == $unit->id ? 'selected' : '' }}> {{$unit->name}}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="name"><span class="text-danger">*</span> 預設價錢：</label>
    <input type="number" step="0.01" name="price" id="price" value="{{ old('price') ?? $material_module->price }}"
        class="form-control w-auto d-inline-block" size="40" placeholder="請輸入價錢" />
</div>

<div class="form-group">
    <label for="memo" class="align-top">模組說明：</label>

    <textarea name="memo" id="memo"
        cols="50" rows="3"
        placeholder="請輸入模組說明"
        class="form-control">{{ old('memo') ?? $material_module->memo }}</textarea>
</div>

{{-- 物料清單 --}}
<material-table
    :materials="materials"
    :units="units"
    :module="false"
    ref="materialTable">
</material-table>

{{-- 附件清單 --}}
{{-- @include('b4.file') --}}
<file-table
    :files="files"
    ref="fileTable">
</file-table>
