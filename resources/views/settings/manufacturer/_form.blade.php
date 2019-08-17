@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>加工廠商編號：</label>
            @if (\Request::route()->getName() == 'manufacturer.create')
                <div class="text-danger">自動產生</div>
            @elseif (\Request::route()->getName() == 'manufacturer.edit' || \Request::route()->getName() == 'manufacturer.show')
                <div class="text-primary">{{ $manufacturer->code }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="gpn">統一編號：</label>
            <input type="text" name="gpn" class="form-control" id="gpn" value="{{ old('gpn') ?? $manufacturer->gpn }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="fullName"><span style="color:red;">*</span>全名</label>
            <input type="text" name="fullName" class="form-control" id="fullName" value="{{ old('fullName') ?? $manufacturer->fullName }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="shortName"><span style="color:red;">*</span>簡稱</label>
            <input type="text" name="shortName" class="form-control" id="shortName" value="{{ old('shortName') ?? $manufacturer->shortName }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="category">分類</label>
            <select class="form-control" id="category" name="category">
                <option value="" {{ (old('category') ?? $manufacturer->category) == '' ? 'selected' : '' }}>請選擇</option>
                <option value="1" {{ (old('category') ?? $manufacturer->category) == 1 ? 'selected' : '' }}>常用</option>
                <option value="2" {{ (old('category') ?? $manufacturer->category) == 2 ? 'selected' : '' }}>不常用</option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="pay">付款方式</label>
            <select class="form-control" id="pay" name="pay">
                <option value="" {{ (old('pay') ?? $manufacturer->pay) == '' ? 'selected' : '' }}>請選擇</option>
                <option value="1" {{ (old('pay') ?? $manufacturer->pay) == 1 ? 'selected' : '' }}>現金</option>
                <option value="2" {{ (old('pay') ?? $manufacturer->pay) == 2 ? 'selected' : '' }}>支票</option>
                <option value="3" {{ (old('pay') ?? $manufacturer->pay) == 3 ? 'selected' : '' }}>轉帳</option>
                <option value="4" {{ (old('pay') ?? $manufacturer->pay) == 4 ? 'selected' : '' }}>其他</option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="receiving">收貨方式</label>
            <select class="form-control" id="receiving" name="receiving">
                <option value="" {{ (old('receiving') ?? $manufacturer->receiving) == '' ? 'selected' : '' }}>請選擇</option>
                <option value="1" {{ (old('receiving') ?? $manufacturer->receiving) == 1 ? 'selected' : '' }}>親送</option>
                <option value="2" {{ (old('receiving') ?? $manufacturer->receiving) == 2 ? 'selected' : '' }}>貨運</option>
                <option value="3" {{ (old('receiving') ?? $manufacturer->receiving) == 3 ? 'selected' : '' }}>自取</option>
                <option value="4" {{ (old('receiving') ?? $manufacturer->receiving) == 4 ? 'selected' : '' }}>其他</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="owner">負責人</label>
            <input type="text" name="owner" class="form-control" id="owner" value="{{ old('owner') ?? $manufacturer->owner }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="contact">聯絡人</label>
            <input type="text" name="contact" class="form-control" id="contact" value="{{ old('contact') ?? $manufacturer->contact }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="tel">電話</label>
            <input type="text" name="tel" class="form-control" id="tel" value="{{ old('tel') ?? $manufacturer->tel }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="fax">傳真</label>
            <input type="text" name="fax" class="form-control" id="fax" value="{{ old('fax') ?? $manufacturer->fax }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="address">地址</label>
            <input type="text" name="address" class="form-control" id="address" value="{{ old('address') ?? $manufacturer->address }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') ?? $manufacturer->email }}">
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <label for="invoiceTitle">發票抬頭</label>
            <input type="text" name="invoiceTitle" class="form-control" id="invoiceTitle" value="{{ old('invoiceTitle') ?? $manufacturer->invoiceTitle }}">
        </div>
    </div>
    <div class="col-md-7">
        <div class="form-group">
            <label for="invoiceAddress">發票地址</label>
            <input type="text" name="invoiceAddress" class="form-control" id="invoiceAddress" value="{{ old('invoiceAddress') ?? $manufacturer->invoiceAddress }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="website">網站</label>
            <input type="text" name="website" class="form-control" id="website" value="{{ old('website') ?? $manufacturer->website }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="items">品項</label>
            <input type="text" name="items" class="form-control" id="items" value="{{ old('items') ?? $manufacturer->items }}">
        </div>
    </div>

    @for ($i = 1; $i <= 3; $i++)
        <div class="col-md-4">
            <div class="form-group form-md-line-input">
                <label for="contact{{ $i }}">聯絡方式 {{ $i }} :</label>
                <select class="form-control" id="contact{{ $i }}" name="contact{{ $i }}">
                    <option value="" {{ (old("contact$i") ?? $manufacturer->{"contact$i"}) == '' ? 'selected' : '' }}>請選擇</option>
                    <option value="1" {{ (old("contact$i") ?? $manufacturer->{"contact$i"}) == 1 ? 'selected' : '' }}>市話</option>
                    <option value="2" {{ (old("contact$i") ?? $manufacturer->{"contact$i"}) == 2 ? 'selected' : '' }}>手機</option>
                    <option value="3" {{ (old("contact$i") ?? $manufacturer->{"contact$i"}) == 3 ? 'selected' : '' }}>Line</option>
                    <option value="4" {{ (old("contact$i") ?? $manufacturer->{"contact$i"}) == 4 ? 'selected' : '' }}>Email</option>
                    <option value="5" {{ (old("contact$i") ?? $manufacturer->{"contact$i"}) == 5 ? 'selected' : '' }}>其他</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="contactContent{{ $i }}">聯絡內容</label>
                <input type="text" name="contactContent{{ $i }}" class="form-control" id="contactContent{{ $i }}" value="{{ old("contactContent$i") ?? $manufacturer->{"contactContent$i"} }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="contactPerson{{ $i }}">聯絡人</label>
                <input type="text" name="contactPerson{{ $i }}" class="form-control" id="contactPerson{{ $i }}" value="{{ old("contactPerson$i") ?? $manufacturer->{"contactPerson$i"} }}">
            </div>
        </div>
    @endfor

    <div class="col-md-12">
        <div class="form-group">
            <label for="memo">備註</label>
            <textarea class="form-control" rows="3" name="memo" id="memo">{{ old('memo') ?? $manufacturer->memo }}</textarea>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="status1">啟用狀態：</label>
            <div class="custom-control custom-radio d-inline-block">
                <input class="custom-control-input" type="radio" name="status" id="status1" value="1" {{ old('status') == 1 || $manufacturer->status == 1 ? 'checked' : '' }}>
                <label class="custom-control-label text-left text-primary" for="status1">啟用</label>
            </div>
            <div class="custom-control custom-radio d-inline-block ml-3">
                <input class="custom-control-input" type="radio" name="status" id="status2" value="2" {{ old('status') == 2 || $manufacturer->status == 2 ? 'checked' : '' }}>
                <label class="custom-control-label text-left text-danger" id="red-label" for="status2">關閉</label>
            </div>
        </div>
    </div>
</div>
