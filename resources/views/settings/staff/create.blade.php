@extends('layouts.app')

@section('title','員工資料建立')

@section('css')
<style>
    /* 初始label顏色 */
    .form-group.form-md-line-input.form-md-floating-label .form-control ~ label {
        color: #43a546; }
    /* help-block顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .help-block, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .help-block {
        color: #43a546;}
    /* focus後的label顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus {
        color: #43a546; }
    /* focus後的底線顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus:after, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus:after {
        background: #43a546; }

    

    .form-group.form-md-line-input .form-control::-moz-placeholder {
      color: #43a546;}
    .form-group.form-md-line-input .form-control:-ms-input-placeholder {
      color: #43a546; }
    .form-group.form-md-line-input .form-control::-webkit-input-placeholder {
      color: #43a546; }

    .form-horizontal .form-group.form-md-line-input > label {
    
    color: #43a546;
    }

    button[type=submit]{
        color:#fff;
        background-color: #43a546;
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
    <h1 class="page-title"> 員工資料建立
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
                <form role="form" action="{{ route('staff.store') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-body">

                    <div class="col-md-12">

                    <div class="col-md-3">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="staff_code" class="form-control" id="form_control_1" value="{{ old('staff_code') }}">
                            <label for="form_control_1"><span style="color:red;">*</span>員工編號</label>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="fullname" class="form-control" id="form_control_2" value="{{ old('fullname') }}">
                            <label for="form_control_2"><span style="color:red;">*</span>姓名</label>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-md-line-input">
                            <select class="form-control" id="form_control_3" name="department">
                                <option value="" {{ old('department')=='' ? 'selected' : '' }}>選擇部門</option>
                                @foreach($deps as $dep)
                                    <option value="{{$dep->id}}" {{ old('department') == $dep->id ? 'selected' : '' }}>{{$dep->name}} </option>
                                @endforeach
                                
                            </select>
                            <label for="form_control_3" style="color:#43a546;"><span style="color:red;">*</span>部門</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        
                        <div class="form-group form-md-line-input">
                            <select class="form-control" id="form_control_4" name="pro_title">
                                <option value="" {{ old('pro_title')=='' ? 'selected' : '' }}>選擇職稱</option>
                                @foreach($pro_titles as $pro_title)
                                    <option value="{{$pro_title->id}}" {{ old('pro_title') == $pro_title->id ? 'selected' : '' }}>{{$pro_title->name}} </option>
                                @endforeach
                            </select>
                            <label for="form_control_4" style="color:#43a546;"><span style="color:red;">*</span>職稱</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="tel" class="form-control" id="form_control_5" value="{{ old('tel') }}">
                            <label for="form_control_5">電話</label>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="mobile" class="form-control" id="form_control_6" value="{{ old('mobile') }}">
                            <label for="form_control_6">手機</label>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="address" class="form-control" id="form_control_7" value="{{ old('address') }}">
                            <label for="form_control_7">地址</label>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="text" name="username" class="form-control" id="form_control_8" value="{{ old('username') }}">
                            <label for="form_control_8"><span style="color:red;">*</span>帳號</label>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="password" name="password" class="form-control" id="form_control_9">
                            <label for="form_control_9"><span style="color:red;">*</span>密碼</label>
                            <span class="help-block">最少8個英文或數字組成</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="password" name="password_confirmation" class="form-control" id="form_control_10">
                            <label for="form_control_10"><span style="color:red;">*</span>再次確認密碼</label>
                            <span class="help-block">最少8個英文或數字組成</span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group form-md-line-input form-md-floating-label">
                            <input type="email" name="email" class="form-control" id="form_control_11" value="{{ old('email') }}">
                            <label for="form_control_11">Email</label>
                            <span class="help-block"></span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group form-md-line-input">
                            <textarea class="form-control" rows="3" name="memo" id="memo">{{ old('memo') }}</textarea>
                            <label for="memo" style="color:#43a546;font-size: 16px;">備註</label>
                        </div>
                    </div>

                    <div class="form-group form-md-line-input">
                        <label class="col-md-2 control-label" for="form_control_12" style="font-size:16px;color:#43a546;">啟用狀態</label>
                        <div class="col-md-10">
                            <div class="md-radio-inline">
                                <div class="md-radio has-info">
                                    <input type="radio" id="radio1" name="status" class="md-radiobtn" value="1"
                                    @if(old('status') == 1 || old('status') == null)
                                        checked
                                    @endif
                                    >
                                    <label for="radio1">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> 啟用 </label>
                                </div>
                                <div class="md-radio has-error">
                                    <input type="radio" id="radio2" name="status" class="md-radiobtn" value="2" {{ old('status') == 2 ? 'checked' : '' }}>
                                    <label for="radio2">
                                        <span></span>
                                        <span class="check"></span>
                                        <span class="box"></span> 關閉 </label>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">    
                    <hr>                
                    <div class="form-actions noborder">
                        <button type="submit" class="btn"><i class="fa fa-check"></i> 存 檔</button>
                        <a href="{{ route('staff.index') }}" class="btn red"><i class="fa fa-times"></i> 取 消</a>
                    </div>
                    </div>


                </form>
            </div>
        </div>
        <!-- END SAMPLE FORM PORTLET-->
    
    
    </div>
</div>




@endsection


@section('scripts')
<script>
    $('#form_control_1').focus();

</script>
@endsection