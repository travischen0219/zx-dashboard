@include('b4.error')

<div class="form-group">
    <label for="code"><span class="text-danger">*</span> 編號：</label>
    {{ $material_module->code }}
</div>

<div class="form-group">
    <label for="name"><span class="text-danger">*</span> 名稱：</label>
    <input type="text" name="name" id="name" value="{{ old('name') ?? $material_module->name }}"
        class="form-control" placeholder="請輸入名稱" />
</div>

<div class="form-group">
    <label for="memo" class="align-top">模組說明：</label>

    <textarea name="memo" id="memo"
        cols="50" rows="5"
        placeholder="請輸入模組說明"
        class="form-control">{{ old('memo') ?? $material_module->memo }}</textarea>
</div>

{{-- 物料清單 --}}
<material-table
    :rows="rows"
    :units="units"
    :module="false"
    ref="materialTable">
</material-table>

{{-- 附件清單 --}}
@include('b4.file')
