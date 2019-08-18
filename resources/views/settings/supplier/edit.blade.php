@extends('b4.app')

@section('title', '修改供應商')
@section('page-header')
    <i class="fas fa-industry active-color"></i>
    @if ($show == 1)
        基本資料 - 檢視供應商
    @else
        基本資料 - 修改供應商
    @endif
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

    @if ($show == 1)
        <style>
            form {
                cursor: not-allowed;
            }

            form * {
                pointer-events: none;
            }
        </style>
    @endif
@endsection

@section('content')
    {!! Form::open([
        'url' => route('supplier.update', $supplier->id),
        'method' => 'PUT',
        'class' => 'form'
    ]) !!}
    @include('settings.supplier._form')

    <div class="form-group mt-3">
        @if ($show != 1)
            <label></label>

            <button type="submit" class="btn btn-primary">修改供應商</button>
            <button type="button" onclick="location.href='{{ route('supplier.index') }}'" class="btn btn-link ml-3">取消</button>
        @endif
    </div>
    {!! Form::close() !!}

    @if ($show == 1)
        <div class="form-group mt-3 text-center">
            <button type="button" onclick="location.href='{{ route('supplier.index') }}'" class="btn btn-link ml-3">返回</button>
        </div>
    @endif
@endsection

@section('script')
    @include('b4.alert')
@endsection
