@include('b4.error')

<div class="row">

    <div class="col-12">
        <div class="form-group">
            <label for="material_category"><span class="text-danger">*</span>分類：</label>

            @if (\Request::route()->getName() == 'material.create')
                <select class="form-control d-inline-block"
                    id="material_category"
                    name="material_category"
                    style="width: auto;"
                    onchange="getCal($(this).val()); showFullCode();">
                    <option value="" {{ old('material_category') == '' ? 'selected' : '' }}>請選擇</option>
                    @foreach($material_categories as $cate)
                        <option value="{{$cate->code}}" {{ old('material_category') == $cate->code ? 'selected' : '' }}>[ {{$cate->code}} ] {{$cate->name}} </option>
                    @endforeach
                </select>
            @elseif (\Request::route()->getName() == 'material.edit' || \Request::route()->getName() == 'material.show')
                <span class="text-primary">
                    [{{ $material->material_category_name->code }}]
                    {{ $material->material_category_name->name }}
                </span>
                <input type="hidden" name="material_category" id="material_category" value="{{ $material->material_category_name->code }}">
            @endif

        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label>最新編號：</label>
            {{ $lastFullCode}}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for=""><span class="text-danger">*</span>物料編號：</label>

            @if (\Request::route()->getName() == 'material.create')
                <input type="text"
                    name="code_1" id="code_1"
                    maxlength="3"
                    class="form-control"
                    placeholder="選填"
                    onkeyup="showFullCode();"
                    onchange="showFullCode();"
                    value="{{ old('code_1') }}" />

                <span class="mx-2">－</span>

                <input type="text"
                    name="code_2" id="code_2"
                    maxlength="3"
                    class="form-control"
                    onkeyup="showFullCode();"
                    onchange="showFullCode();"
                    value="{{ old('code_2') }}" />

                <span class="mx-2">－</span>

                <input type="text"
                    name="code_3" id="code_3"
                    maxlength="5"
                    class="form-control"
                    onkeyup="showFullCode();"
                    onchange="showFullCode();"
                    placeholder="選填"
                    value="{{ old('code_3') }}" />
            @elseif (\Request::route()->getName() == 'material.edit' || \Request::route()->getName() == 'material.show')
                <span class="text-primary">
                    {{ $material->fullCode }}
                </span>
                <input type="hidden" name="fullCode" value="{{ $material->fullCode }}">
            @endif
        </div>
    </div>

    @if (\Request::route()->getName() == 'material.create')
        <div class="col-md-12">
            <div class="form-group">
                <label>完整編號：</label>
                <span id="fullCode"></span>
            </div>
        </div>
    @endif

    <div class="col-md-12">
        <div class="form-group">
            <label for="fullName"><span class="text-danger">*</span>品名：</label>
            <input type="text" class="form-control"
                name="fullName" id="fullName"
                size="82"
                value="{{ old('fullName') ?? $material->fullName }}" />
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="unit"><span class="text-danger">*</span>單位：</label>
            <select class="form-control d-inline-block" id="unit" name="unit" style="width: auto;">
                <option value="0">請選擇 (需指定後才能進行採購進貨操作)</option>
                @foreach($material_units as $unit)
                    <option value="{{ $unit->id }}" {{ (old('unit') ?? $material->unit) == $unit->id ? 'selected' : '' }}> {{$unit->name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group text-primary mb-2">
            <label></label>
            預設每單位的價錢 (請輸入數字)
        </div>
        <div class="form-group">
            <label for="cost"><span class="text-danger">成本</span>：</label>
            <input type="text"
                name="cost"
                class="form-control"
                id="cost"
                size="30"
                value="{{ old('cost') ?? $material->cost }}" />

            <label for="cost" class="ml-3"><span class="text-danger">售價</span>：</label>
            <input type="text"
                name="price"
                class="form-control"
                id="price"
                size="30"
                value="{{ old('price') ?? $material->price }}" />
        </div>
    </div>

    <div id="cal-column" class="border bg-white col-md-10 m-3 p-3" style="display: block;">
        <div class="form-group text-danger pl-4">
            有指定計價欄位的分類才會出現
        </div>

        <div class="form-group">
            <label for="">計價單位：</label>
            <select class="form-control d-inline-block w-auto" id="cal_unit" name="cal_unit">
                {{-- <option value="0"> 未指定</option> --}}
                @foreach($material_units as $unit)
                    <option value="{{ $unit->id }}" {{ (old('cal_unit') ?? $material->cal_unit) == $unit->id ? 'selected' : '' }}> {{$unit->name}}</option>
                @endforeach
            </select>
            <label for="cal_price" class="w-auto nowrap ml-3">計價價格 (請輸入數字)</label>
            <input type="text" name="cal_price" class="form-control" id="cal_price" value="{{ old('cal_price') ?? $material->cal_price }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="size">尺寸：</label>
            <input type="text"
                name="size"
                class="form-control"
                id="size"
                size="30"
                value="{{ old('size') ?? $material->size }}" />

            <label for="color" class="ml-3">顏色：</label>
            <input type="text"
                name="color"
                class="form-control"
                id="color"
                size="30"
                value="{{ old('color') ?? $material->color }}" />
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group text-primary mb-2">
            <label></label>
            預設的量 (請輸入數字)
        </div>
        <div class="form-group">
            <label for="buy"><span class="text-danger">採購量</span>：</label>
            <input type="text"
                name="buy"
                class="form-control"
                id="buy"
                size="30"
                value="{{ old('buy') ?? $material->buy }}" />

            <label for="safe" class="ml-3"><span class="text-danger">安全量</span>：</label>
            <input type="text"
                name="safe"
                class="form-control"
                id="safe"
                size="30"
                value="{{ old('safe') ?? $material->safe }}" />
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="memo" class="align-top">備註：</label>
            <textarea class="form-control"
                rows="3" cols="84"
                name="memo" id="memo">{{ old('memo') ?? $material->memo }}</textarea>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="status1">啟用狀態：</label>
            <div class="custom-control custom-radio d-inline-block">
                <input class="custom-control-input" type="radio" name="status" id="status1" value="1" {{ old('status') == 1 || $material->status == 1 ? 'checked' : '' }}>
                <label class="custom-control-label text-left text-primary w-auto" for="status1">啟用</label>
            </div>
            <div class="custom-control custom-radio d-inline-block ml-3">
                <input class="custom-control-input" type="radio" name="status" id="status2" value="2" {{ old('status') == 2 || $material->status == 2 ? 'checked' : '' }}>
                <label class="custom-control-label text-left text-danger w-auto" id="red-label" for="status2">關閉</label>
            </div>
        </div>
    </div>

</div>

<input type="hidden" name="fullCode" id="fullCode_input">

{{-- 附件清單 --}}
<file-table
    :files="files"
    ref="fileTable">
</file-table>
