@extends('b4.app')

@section('title', '批號管理')
@section('page-header')
    <i class="fas fa-tasks active-color mr-1"></i>基本資料 - 批號建立
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
        'url' => route('lot.store'),
        'class' => 'form'
    ]) !!}
        @include('settings.lot._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">新增批號</button>
            <button type="button" onclick="location.href='{{ route('lot.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}
@endsection

@section('script')
    @include('settings.lot._script')
    @include('b4.alert')
@endsection
