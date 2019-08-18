@include('b4.error')

<div class="form-group form-md-line-input form-md-floating-label">
    <label for="code"><span class="text-danger">*</span>分類代號：</label>
    <input type="text" name="code" class="form-control" id="code" value="{{ old('code') ?? $cate->code }}">
</div>

<div class="form-group form-md-line-input form-md-floating-label">
    <label for="name"><span class="text-danger">*</span>分類名稱：</label>
    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') ?? $cate->name }}">
</div>

<div class="form-group d-inline-block">
    <label for="cal1">計價欄位：</label>
    <div class="custom-control custom-radio d-inline-block">
        <input class="custom-control-input" type="radio" name="cal" id="cal1" value="1" {{ old('cal') == 1 || $cate->cal == 1 ? 'checked' : '' }}>
        <label class="custom-control-label text-left text-primary" for="cal1">有</label>
    </div>
    <div class="custom-control custom-radio d-inline-block">
        <input class="custom-control-input" type="radio" name="cal" id="cal2" value="2" {{ old('cal') != 1 && $cate->cal != 1 ? 'checked' : '' }}>
        <label class="custom-control-label text-left text-danger" id="red-label" for="cal2">無</label>
    </div>
</div>
