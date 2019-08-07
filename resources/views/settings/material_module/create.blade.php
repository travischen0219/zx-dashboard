@extends('b4.app')

@section('title', '物料模組')
@section('page-header')
    <i class="fab fa-buromobelexperte active-color"></i> 基本資料 - 新增物料模組
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
    </style>
@endsection

@section('content')
    {!! Form::open([
        'url' => route('material_module.store'),
        'class' => 'form',
        'id' => 'app',
        'files' => true
    ]) !!}
    @include('settings.material_module._form')

    <div class="form-group mt-3">
        <label></label>

        <button type="submit" class="btn btn-primary">新增物料模組</button>
        <button type="button" onclick="location.href='{{ route('material_module.index') }}'" class="btn btn-link ml-3">取消</button>
    </div>
    {!! Form::close() !!}
@endsection

@section('script')
    @include('settings.material_module._script')
@endsection
