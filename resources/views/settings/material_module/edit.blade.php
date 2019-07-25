@extends('b4.app')

@section('title', '物料模組')
@section('page-header')
    <i class="fab fa-buromobelexperte active-color"></i>
    @if ($show == 1)
        基本資料 - 檢視物料模組
    @else
        基本資料 - 修改物料模組
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

        @if ($show == 1)
            form {
                cursor: not-allowed;
            }

            form * {
                pointer-events: none;
            }
        @endif
    </style>
@endsection

@section('content')
    {!! Form::open([
        'url' => route('material_module.update', $material_module->id),
        'method' => 'PUT',
        'class' => 'form',
        'id' => 'app',
        'files' => true
    ]) !!}

    @include('settings.material_module._form')

    <div class="form-group mt-3 text-center">
        @if ($show != 1)
            <button type="submit" class="btn btn-primary">修改物料模組</button>
            <button type="button" onclick="location.href='{{ route('material_module.index') }}'" class="btn btn-link ml-3">取消</button>
        @endif
    </div>
    {!! Form::close() !!}

    @if ($show == 1)
        <div class="form-group mt-3 text-center">
            <button type="button" onclick="location.href='{{ route('material_module.index') }}'" class="btn btn-link ml-3">返回</button>
        </div>
    @endif
@endsection

@section('script')
    @include('settings.material_module._script')
@endsection
