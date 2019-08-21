@extends('b4.app')

@section('title', '銷貨出貨 - 修改銷貨')
@section('page-header')
    <i class="fas fa-dolly-flatbed active-color mr-2"></i>銷貨出貨 - 修改銷貨
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
        'url' => route('out.update', $out->id),
        'method' => 'PUT',
        'class' => 'form mb-5',
        'id' => 'app'
    ]) !!}
        @include('shopping.out._form')

        <div class="form-group mt-3 text-center">
            <button type="submit" class="btn btn-primary px-5">
                <i class="fas fa-check mr-1"></i> 修改銷貨
            </button>
            <button type="button" onclick="location.href='{{ route('out.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}
@endsection

@section('script')
    @include('shopping.out._script')
@endsection
