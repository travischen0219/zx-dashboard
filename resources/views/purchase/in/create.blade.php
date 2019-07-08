@extends('b4.app')

@section('title', '採購進貨 - 新增採購')
@section('page-header')
    <i class="fas fa-tasks active-color mr-1"></i>採購進貨 - 新增採購
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
        'url' => route('in.store'),
        'class' => 'form',
        'id' => 'app'
    ]) !!}
        @include('purchase.in._form')

        <div class="form-group mt-3 text-center">
            <button type="submit" class="btn btn-primary px-5">
                <i class="fas fa-check mr-1"></i> 新增採購
            </button>
            <button type="button" onclick="location.href='{{ route('in.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}
@endsection

@section('script')
    @include('purchase.in._script')
@endsection
