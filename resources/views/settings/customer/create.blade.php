@extends('b4.app')

@section('title', '新增客戶')
@section('page-header')
    <i class="fas fa-user-tie active-color"></i> 基本資料 - 新增客戶
@endsection

@section('css')
<style>
    .form label {
        width: auto;
        text-align: left;
        color: #248ff1;
    }
    .form input[type=text], .form textarea {
        display: block;
        width: 100%;
    }
</style>
@endsection

@section('content')
    {!! Form::open([
        'url' => route('customer.store'),
        'class' => 'form'
    ]) !!}
    @include('settings.customer._form')

    <div class="form-group mt-3">
        <label></label>

        <button type="submit" class="btn btn-primary">新增客戶</button>
        <button type="button" onclick="location.href='{{ route('customer.index') }}'" class="btn btn-link ml-3">取消</button>
    </div>
    {!! Form::close() !!}
@endsection

@section('script')
    @include('b4.alert')
@endsection
