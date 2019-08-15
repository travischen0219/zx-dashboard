@include('includes.messages')

<div class="form-group form-md-line-input form-md-floating-label">
    <label for="name">職稱名稱：</label>
    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') ?? $pro_title->name }}">
</div>
