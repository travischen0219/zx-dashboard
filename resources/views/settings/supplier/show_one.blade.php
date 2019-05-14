@extends('layouts.app')

@section('title','供應商查詢')

@section('css')
<style>
    /* 初始label顏色 */
    .form-group.form-md-line-input.form-md-floating-label .form-control ~ label {
        color: #248ff1; }
    /* help-block顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .help-block, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .help-block {
        color: #248ff1;}
    /* focus後的label顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus {
        color: #248ff1; }
    /* focus後的底線顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus:after, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus:after {
        background: #248ff1; }

    

    .form-group.form-md-line-input .form-control::-moz-placeholder {
      color: #248ff1;}
    .form-group.form-md-line-input .form-control:-ms-input-placeholder {
      color: #248ff1; }
    .form-group.form-md-line-input .form-control::-webkit-input-placeholder {
      color: #248ff1; }

    .form-horizontal .form-group.form-md-line-input > label {
      color: #248ff1;}

    button[type=submit]{
        color:#fff;
        background-color: #248ff1;
    }

</style>


@endsection

@section('page_header')
<!-- BEGIN PAGE HEADER-->

<!-- BEGIN PAGE BAR -->
<div class="page-bar">

    <!-- BEGIN THEME PANEL -->
    @include('layouts.theme_panel')    
    <!-- END THEME PANEL -->


    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 供應商查詢
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
    <div class="col-md-12 ">
        <!-- BEGIN SAMPLE FORM PORTLET-->
        <div class="portlet light">
            @include('includes.messages')

            <div class="portlet-body form">
      
                <div class="form-body">

                    <div class="col-md-12">

                        <div class="col-md-3">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="code" class="form-control" id="form_control_1" value="{{$supplier->code}}" readonly>
                                <label for="form_control_1">供應商編號</label>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="gpn" class="form-control" id="form_control_2" value="{{ $supplier->gpn }}" readonly>
                                <label for="form_control_2">統一編號</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="fullName" class="form-control" id="form_control_3" value="{{ $supplier->fullName }}" readonly>
                                <label for="form_control_3">全名</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="shortName" class="form-control" id="form_control_4" value="{{ $supplier->shortName }}" readonly>
                                <label for="form_control_4">簡稱</label>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-md-line-input">
                                <select class="form-control" id="form_control_5" name="category" disabled>
                                    <option value="" {{ $supplier->category == '' ? 'selected' : '' }}>請選擇</option>
                                    <option value="1" {{ $supplier->category == 1 ? 'selected' : '' }}>常用</option>
                                    <option value="2" {{ $supplier->category == 2 ? 'selected' : '' }}>不常用</option>
                                </select>
                                <label for="form_control_5" style="color:#248ff1;">分類</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-md-line-input">
                                <select class="form-control" id="form_control_6" name="pay" disabled>
                                    <option value="" {{ $supplier->pay == '' ? 'selected' : '' }}>請選擇</option>
                                    <option value="1" {{ $supplier->pay == 1 ? 'selected' : '' }}>現金</option>
                                    <option value="2" {{ $supplier->pay == 2 ? 'selected' : '' }}>支票</option>                                
                                    <option value="3" {{ $supplier->pay == 3 ? 'selected' : '' }}>轉帳</option>
                                    <option value="4" {{ $supplier->pay == 4 ? 'selected' : '' }}>其他</option>

                                </select>
                                <label for="form_control_6" style="color:#248ff1;">付款方式</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-md-line-input">
                                <select class="form-control" id="form_control_7" name="receiving" disabled>
                                    <option value="" {{ $supplier->receiving == '' ? 'selected' : '' }}>請選擇</option>
                                    <option value="1" {{ $supplier->receiving == 1 ? 'selected' : '' }}>親送</option>
                                    <option value="2" {{ $supplier->receiving == 2 ? 'selected' : '' }}>貨運</option>                                
                                    <option value="3" {{ $supplier->receiving == 3 ? 'selected' : '' }}>自取</option>
                                    <option value="4" {{ $supplier->receiving == 4 ? 'selected' : '' }}>其他</option>
                                </select>
                                <label for="form_control_7" style="color:#248ff1;">收貨方式</label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="owner" class="form-control" id="form_control_8" value="{{ $supplier->owner }}" readonly>
                                <label for="form_control_8">負責人</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="contact" class="form-control" id="form_control_9" value="{{ $supplier->contact }}" readonly>
                                <label for="form_control_9">聯絡人</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="tel" class="form-control" id="form_control_10" value="{{ $supplier->tel }}" readonly>
                                <label for="form_control_10">電話</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="fax" class="form-control" id="form_control_11" value="{{ $supplier->fax }}" readonly>
                                <label for="form_control_11">傳真</label>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="address" class="form-control" id="form_control_12" value="{{ $supplier->address }}" readonly>
                                <label for="form_control_12">地址</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="email" name="email" class="form-control" id="form_control_13" value="{{ $supplier->email }}" readonly>
                                <label for="form_control_13">Email</label>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="invoiceTitle" class="form-control" id="form_control_14" value="{{ $supplier->invoiceTitle }}" readonly>
                                <label for="form_control_14">發票抬頭</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="invoiceAddress" class="form-control" id="form_control_15" value="{{ $supplier->invoiceAddress }}" readonly>
                                <label for="form_control_15">發票地址</label>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="website" class="form-control" id="form_control_16" value="{{ $supplier->website }}" readonly>
                                <label for="form_control_16">網站</label>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="items" class="form-control" id="form_control_17" value="{{ $supplier->items }}" readonly>
                                <label for="form_control_17">品項</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group form-md-line-input">
                                <select class="form-control" id="form_control_18" name="contact1" disabled>
                                    <option value="" {{ $supplier->contact1 == '' ? 'selected' : '' }}>請選擇</option>
                                    <option value="1" {{ $supplier->contact1 == 1 ? 'selected' : '' }}>市話</option>
                                    <option value="2" {{ $supplier->contact1 == 2 ? 'selected' : '' }}>手機</option>
                                    <option value="3" {{ $supplier->contact1 == 3 ? 'selected' : '' }}>Line</option>
                                    <option value="4" {{ $supplier->contact1 == 4 ? 'selected' : '' }}>Email</option>
                                    <option value="5" {{ $supplier->contact1 == 5 ? 'selected' : '' }}>其他</option>
                                </select>
                                <label for="form_control_18" style="color:#248ff1;">聯絡方式 1 :</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="contactContent1" class="form-control" id="form_control_19" value="{{ $supplier->contactContent1 }}" readonly>
                                <label for="form_control_19">聯絡內容</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="contactPerson1" class="form-control" id="form_control_20" value="{{ $supplier->contactPerson1 }}" readonly>
                                <label for="form_control_20">聯絡人</label>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-md-line-input">
                                <select class="form-control" id="form_control_21" name="contact2" disabled>
                                    <option value="" {{ $supplier->contact2 == '' ? 'selected' : '' }}>請選擇</option>
                                    <option value="1" {{ $supplier->contact2 == 1 ? 'selected' : '' }}>市話</option>
                                    <option value="2" {{ $supplier->contact2 == 2 ? 'selected' : '' }}>手機</option>
                                    <option value="3" {{ $supplier->contact2 == 3 ? 'selected' : '' }}>Line</option>
                                    <option value="4" {{ $supplier->contact2 == 4 ? 'selected' : '' }}>Email</option>
                                    <option value="5" {{ $supplier->contact2 == 5 ? 'selected' : '' }}>其他</option>
                                </select>
                                <label for="form_control_21" style="color:#248ff1;">聯絡方式 2 :</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="contactContent2" class="form-control" id="form_control_22" value="{{ $supplier->contactContent2 }}" readonly>
                                <label for="form_control_22">聯絡內容</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="contactPerson2" class="form-control" id="form_control_23" value="{{ $supplier->contactPerson2 }}" readonly>
                                <label for="form_control_23">聯絡人</label>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group form-md-line-input">
                                <select class="form-control" id="form_control_24" name="contact3" disabled>
                                    <option value="" {{ $supplier->contact3 == '' ? 'selected' : '' }}>請選擇</option>
                                    <option value="1" {{ $supplier->contact3 == 1 ? 'selected' : '' }}>市話</option>
                                    <option value="2" {{ $supplier->contact3 == 2 ? 'selected' : '' }}>手機</option>
                                    <option value="3" {{ $supplier->contact3 == 3 ? 'selected' : '' }}>Line</option>
                                    <option value="4" {{ $supplier->contact3 == 4 ? 'selected' : '' }}>Email</option>
                                    <option value="5" {{ $supplier->contact3 == 5 ? 'selected' : '' }}>其他</option>
                                </select>
                                <label for="form_control_24" style="color:#248ff1;">聯絡方式 3 :</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="contactContent3" class="form-control" id="form_control_25" value="{{ $supplier->contactContent3 }}" readonly>
                                <label for="form_control_25">聯絡內容</label>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <input type="text" name="contactPerson3" class="form-control" id="form_control_26" value="{{ $supplier->contactPerson3 }}" readonly>
                                <label for="form_control_26">聯絡人</label>
                                <span class="help-block"></span>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group form-md-line-input">
                                <textarea class="form-control" rows="3" name="memo" id="memo" readonly>{{ $supplier->memo }}</textarea>
                                <label for="memo" style="color:#248ff1;font-size: 16px;">備註</label>
                            </div>
                        </div>
                        
                        <div class="form-group form-md-line-input">
                            <label class="col-md-1 control-label" for="form_control_12" style="font-size:16px;color:#248ff1;">啟用狀態</label>
                            <div class="col-md-11">
                                <div class="md-radio-inline" style="margin-top:0px">
                                        @if($supplier->status == 1)
                                            <span style="font-size: 16px;">啟用</span>
                                        @elseif($supplier->status == 2)
                                            <span style="font-size: 16px;">關閉</span>
                                        @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-top:10px;">
                            <div class="well">
                                <span>最後修改人員 : {{ $updated_user->fullname }} @if($updated_user->delete_flag != 0) <span style="color:red;">(該人員已刪除)</span> @endif</span><br>
                                <span>最後修改時間 : {{ $supplier->updated_at }}</span>
                            </div>
                        </div>


                        <div class="col-md-12">             
                            <div class="form-actions noborder">
                                <a href="{{ route('supplier.index') }}" class="btn blue"><i class="fa fa-reply"></i> 返 回</a>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    </div>
</div>




@endsection


@section('scripts')
<script>


</script>
@endsection