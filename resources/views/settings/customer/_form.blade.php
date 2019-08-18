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
            <label>客戶編號：</label>
            @if (\Request::route()->getName() == 'customer.create')
                <div class="text-danger">自動產生</div>
            @elseif (\Request::route()->getName() == 'customer.edit' || \Request::route()->getName() == 'customer.show')
                <div class="text-primary">{{ $customer->code }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="gpn">統一編號：</label>
            <input type="text" name="gpn" class="form-control" id="gpn" value="{{ old('gpn') ?? $customer->gpn }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="fullName"><span style="color:red;">*</span>全名</label>
            <input type="text" name="fullName" class="form-control" id="fullName" value="{{ old('fullName') ?? $customer->fullName }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="shortName"><span style="color:red;">*</span>簡稱</label>
            <input type="text" name="shortName" class="form-control" id="shortName" value="{{ old('shortName') ?? $customer->shortName }}">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="category">分類</label>
            <select class="form-control" id="category" name="category">
                <option value="" {{ (old('category') ?? $customer->category) == '' ? 'selected' : '' }}>請選擇</option>
                <option value="1" {{ (old('category') ?? $customer->category) == 1 ? 'selected' : '' }}>北部</option>
                <option value="2" {{ (old('category') ?? $customer->category) == 2 ? 'selected' : '' }}>中部</option>
                <option value="3" {{ (old('category') ?? $customer->category) == 3 ? 'selected' : '' }}>南部</option>
                <option value="4" {{ (old('category') ?? $customer->category) == 4 ? 'selected' : '' }}>海外</option>
                <option value="5" {{ (old('category') ?? $customer->category) == 5 ? 'selected' : '' }}>中國大陸</option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="pay">付款方式</label>
            <select class="form-control" id="pay" name="pay">
                <option value="" {{ (old('pay') ?? $customer->pay) == '' ? 'selected' : '' }}>請選擇</option>
                <option value="1" {{ (old('pay') ?? $customer->pay) == 1 ? 'selected' : '' }}>現金</option>
                <option value="2" {{ (old('pay') ?? $customer->pay) == 2 ? 'selected' : '' }}>支票</option>
                <option value="3" {{ (old('pay') ?? $customer->pay) == 3 ? 'selected' : '' }}>轉帳</option>
                <option value="4" {{ (old('pay') ?? $customer->pay) == 4 ? 'selected' : '' }}>其他</option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="receiving">收貨方式</label>
            <select class="form-control" id="receiving" name="receiving">
                <option value="" {{ (old('receiving') ?? $customer->receiving) == '' ? 'selected' : '' }}>請選擇</option>
                <option value="1" {{ (old('receiving') ?? $customer->receiving) == 1 ? 'selected' : '' }}>親送</option>
                <option value="2" {{ (old('receiving') ?? $customer->receiving) == 2 ? 'selected' : '' }}>貨運</option>
                <option value="3" {{ (old('receiving') ?? $customer->receiving) == 3 ? 'selected' : '' }}>自取</option>
                <option value="4" {{ (old('receiving') ?? $customer->receiving) == 4 ? 'selected' : '' }}>其他</option>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            <label for="owner">負責人</label>
            <input type="text" name="owner" class="form-control" id="owner" value="{{ old('owner') ?? $customer->owner }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="contact">聯絡人</label>
            <input type="text" name="contact" class="form-control" id="contact" value="{{ old('contact') ?? $customer->contact }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="tel">電話</label>
            <input type="text" name="tel" class="form-control" id="tel" value="{{ old('tel') ?? $customer->tel }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label for="fax">傳真</label>
            <input type="text" name="fax" class="form-control" id="fax" value="{{ old('fax') ?? $customer->fax }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="address">地址</label>
            <input type="text" name="address" class="form-control" id="address" value="{{ old('address') ?? $customer->address }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') ?? $customer->email }}">
        </div>
    </div>

    <div class="col-md-5">
        <div class="form-group">
            <label for="invoiceTitle">發票抬頭</label>
            <input type="text" name="invoiceTitle" class="form-control" id="invoiceTitle" value="{{ old('invoiceTitle') ?? $customer->invoiceTitle }}">
        </div>
    </div>
    <div class="col-md-7">
        <div class="form-group">
            <label for="invoiceAddress">發票地址</label>
            <input type="text" name="invoiceAddress" class="form-control" id="invoiceAddress" value="{{ old('invoiceAddress') ?? $customer->invoiceAddress }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="website">網站</label>
            <input type="text" name="website" class="form-control" id="website" value="{{ old('website') ?? $customer->website }}">
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="close_date">結帳日</label>
            <input type="text" name="close_date" class="form-control" id="close_date" value="{{ old('close_date') ?? $customer->close_date }}">
        </div>
    </div>

    @for ($i = 1; $i <= 3; $i++)
        <div class="col-md-4">
            <div class="form-group form-md-line-input">
                <label for="contact{{ $i }}">聯絡方式 {{ $i }} :</label>
                <select class="form-control" id="contact{{ $i }}" name="contact{{ $i }}">
                    <option value="" {{ (old("contact$i") ?? $customer->{"contact$i"}) == '' ? 'selected' : '' }}>請選擇</option>
                    <option value="1" {{ (old("contact$i") ?? $customer->{"contact$i"}) == 1 ? 'selected' : '' }}>市話</option>
                    <option value="2" {{ (old("contact$i") ?? $customer->{"contact$i"}) == 2 ? 'selected' : '' }}>手機</option>
                    <option value="3" {{ (old("contact$i") ?? $customer->{"contact$i"}) == 3 ? 'selected' : '' }}>Line</option>
                    <option value="4" {{ (old("contact$i") ?? $customer->{"contact$i"}) == 4 ? 'selected' : '' }}>Email</option>
                    <option value="5" {{ (old("contact$i") ?? $customer->{"contact$i"}) == 5 ? 'selected' : '' }}>其他</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="contactContent{{ $i }}">聯絡內容</label>
                <input type="text" name="contactContent{{ $i }}" class="form-control" id="contactContent{{ $i }}" value="{{ old("contactContent$i") ?? $customer->{"contactContent$i"} }}">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="contactPerson{{ $i }}">聯絡人</label>
                <input type="text" name="contactPerson{{ $i }}" class="form-control" id="contactPerson{{ $i }}" value="{{ old("contactPerson$i") ?? $customer->{"contactPerson$i"} }}">
            </div>
        </div>
    @endfor

    <div class="col-md-12">
        <div class="form-group">
            <label for="memo">備註</label>
            <textarea class="form-control" rows="3" name="memo" id="memo">{{ old('memo') ?? $customer->memo }}</textarea>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="status1">啟用狀態：</label>
            <div class="custom-control custom-radio d-inline-block">
                <input class="custom-control-input" type="radio" name="status" id="status1" value="1" {{ old('status') == 1 || $customer->status == 1 ? 'checked' : '' }}>
                <label class="custom-control-label text-left text-primary" for="status1">啟用</label>
            </div>
            <div class="custom-control custom-radio d-inline-block ml-3">
                <input class="custom-control-input" type="radio" name="status" id="status2" value="2" {{ old('status') == 2 || $customer->status == 2 ? 'checked' : '' }}>
                <label class="custom-control-label text-left text-danger" id="red-label" for="status2">關閉</label>
            </div>
        </div>
    </div>
</div>
