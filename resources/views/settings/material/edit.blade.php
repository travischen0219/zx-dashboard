@extends('b4.app')

@section('title', '物料')
@section('page-header')
    <i class="fas fa-puzzle-piece active-color"></i>
    @if ($show == 1)
        基本資料 - 檢視物料
    @else
        基本資料 - 修改物料
    @endif
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
        'url' => route('material.update', $material->id),
        'method' => 'PUT',
        'class' => 'form',
        'id' => 'app',
        'files' => true
    ]) !!}

    @include('settings.material._form')

    <div class="form-group mt-3 text-center">
        @if ($show != 1)
            <button type="submit" class="btn btn-primary">修改物料</button>
            <button type="button" onclick="location.href='{{ route('material.index') }}'" class="btn btn-link ml-3">取消</button>
        @endif
    </div>
    {!! Form::close() !!}

    @if ($show == 1)
        <div class="form-group mt-3 text-center">
            <button type="button" onclick="location.href='{{ route('material.index') }}'" class="btn btn-link ml-3">返回</button>
        </div>
    @endif
@endsection

@section('script')
    @include('settings.material._script')
    @include('b4.alert')
@endsection
