@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group d-inline-block">
    <label for="staff_code"><span style="color:red;">*</span>員工編號：</label>
    @if (\Request::route()->getName() == 'staff.create')
        <input type="text" name="staff_code" class="form-control" id="staff_code"
            value="{{ old('staff_code') ?? $user->staff_code }}">
    @elseif (\Request::route()->getName() == 'staff.edit')
        <input type="text" name="" class="form-control" id="" readonly
            value="{{ $user->staff_code }}"><span class="text-danger"> 不可修改</span>
    @endif
</div>

<div class="form-group d-inline-block">
    <label for="fullname"><span style="color:red;">*</span>姓名：</label>
    <input type="text" name="fullname" class="form-control" id="fullname"
        value="{{ old('fullname') ?? $user->fullname }}">
</div>

<div class="form-group d-inline-block">
    <label for="department"><span style="color:red;">*</span>部門：</label>
    <select class="form-control w-auto d-inline-block" id="department_id" name="department_id">
        <option value="">選擇部門</option>
        @foreach($deps as $dep)
        <option value="{{$dep->id}}" {{ (old('department_id') ?? $user->department_id) == $dep->id ? 'selected' : '' }}>
            {{$dep->name}}
        </option>
        @endforeach
    </select>
</div>

<div class="form-group d-inline-block">
    <label for="pro_title"><span style="color:red;">*</span>職稱：</label>
    <select class="form-control w-auto d-inline-block" id="professional_title_id" name="professional_title_id">
        <option value="">選擇職稱</option>
        @foreach($pro_titles as $pro_title)
        <option value="{{$pro_title->id}}"
            {{ (old('professional_title_id') ?? $user->professional_title_id) == $pro_title->id ? 'selected' : '' }}>
            {{$pro_title->name}}
        </option>
        @endforeach
    </select>
</div>

<div class="clearfix"></div>

<div class="form-group d-inline-block">
    <label for="tel">電話：</label>
    <input type="text" name="tel" class="form-control" id="tel" value="{{ old('tel') ?? $user->tel }}">
</div>

<div class="form-group d-inline-block">
    <label for="mobile">手機：</label>
    <input type="text" name="mobile" class="form-control" id="mobile" value="{{ old('mobile') ?? $user->mobile }}">
</div>

<div class="form-group d-inline-block">
    <label for="address">地址：</label>
    <input type="text" name="address" class="form-control" size="60" id="address"
        value="{{ old('address') ?? $user->address }}">
</div>

<div class="clearfix"></div>

<div class="form-group d-inline-block align-top">
    <label for="username"><span style="color:red;">*</span>帳號：</label>
    @if (\Request::route()->getName() == 'staff.create')
        <input type="text" name="username" class="form-control" id="username"
            value="{{ old('username') ?? $user->username }}">
    @elseif (\Request::route()->getName() == 'staff.edit')
        <input type="text" name="" class="form-control" id="" readonly
            value="{{ $user->username }}"><span class="text-danger"> 不可修改</span>
    @endif
</div>

<div class="form-group d-inline-block align-top">
    <label for="password"><span style="color:red;">*</span>密碼：</label>
    <input type="password" name="password" class="form-control w-auto d-inline-block" id="password"
        value="{{ old('password') ?? $user->password }}">
    <br>
    <label for="password"></label>
    <span class="help-block text-danger">最少8個英文或數字組成</span>
</div>

<div class="form-group d-inline-block align-top">
    <label for="password_confirmation" style="width: 120px;"><span style="color:red;">*</span>再次確認密碼：</label>
    <input type="password" name="password_confirmation" class="form-control w-auto d-inline-block" id="password_confirmation"
        value="{{ old('password_confirmation') ?? $user->password }}">
    <br>
    <label for="password" style="width: 120px;"></label>
    <span class="help-block text-danger">最少8個英文或數字組成</span>
</div>

<div class="clearfix"></div>

<div class="form-group d-inline-block">
    <label for="email"><span style="color:red;">*</span>Email：</label>
    <input type="text" name="email" class="form-control" size="60" id="email"
        value="{{ old('email') ?? $user->email }}">
</div>

<div class="clearfix"></div>

<div class="form-group d-inline-block">
    <label for="memo" class="align-top">備註：</label>
    <textarea name="memo" class="form-control" cols="50" rows="5" id="memo">{{ old('memo') ?? $user->memo }}</textarea>
</div>

<div class="clearfix"></div>

<div class="form-group d-inline-block">
    <label for="status1">啟用狀態：</label>
    <div class="custom-control custom-radio d-inline-block">
        <input class="custom-control-input" type="radio" name="status" id="status1" value="1" {{ old('status') == 1 || $user->status == 1 ? 'checked' : '' }}>
        <label class="custom-control-label text-left text-primary" for="status1">啟用</label>
    </div>
    <div class="custom-control custom-radio d-inline-block">
        <input class="custom-control-input" type="radio" name="status" id="status2" value="2" {{ old('status') == 2 || $user->status == 2 ? 'checked' : '' }}>
        <label class="custom-control-label text-left text-danger" id="red-label" for="status2">關閉</label>
    </div>
</div>
