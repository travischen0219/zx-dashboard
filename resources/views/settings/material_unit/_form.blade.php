@include('includes.messages')

<div class="form-group form-md-line-input form-md-floating-label">
    <label for="name"><span class="text-danger">*</span>單位名稱：</label>
    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') ?? $unit->name }}">
</div>
