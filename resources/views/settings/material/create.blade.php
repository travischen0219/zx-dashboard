@extends('b4.app')

@section('title', '新增物料')
@section('page-header')
    <i class="fas fa-puzzle-piece active-color"></i> 基本資料 - 新增物料
@endsection

@section('css')
    <style>
        .mfp-wrap {
            z-index: 8000;
        }
        .mfp-iframe-holder .mfp-content {
            width: 85%;
            height: 85%;
            max-width: 100%;
        }
        .form label {
            color: #248ff1;
        }
    </style>
@endsection

@section('content')
    {!! Form::open([
        'url' => route('material.store'),
        'class' => 'form',
        'id' => 'app',
        'files' => true
    ]) !!}
    @include('settings.material._form')

    <div class="form-group mt-3">
        <label></label>

        <button type="submit" class="btn btn-primary">新增物料</button>
        <button type="button" onclick="location.href='{{ route('material.index') }}'" class="btn btn-link ml-3">取消</button>
    </div>
    {!! Form::close() !!}
@endsection

@section('script')
    @include('settings.material._script')
@endsection
