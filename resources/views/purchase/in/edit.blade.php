@extends('b4.app')

@section('title', '採購進貨 - 修改採購')
@section('page-header')
    <i class="fas fa-shopping-cart active-color mr-1"></i>採購進貨 - 修改採購
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
        'url' => route('in.update', $in->id),
        'method' => 'PUT',
        'class' => 'form mb-5',
        'id' => 'app'
    ]) !!}
        @include('purchase.in._form')

        <div class="form-group mt-3 text-center">
            @if (\App\Model\User::canAdmin('purchase'))
                <button type="submit" class="btn btn-primary px-5">
                    <i class="fas fa-check mr-1"></i> 修改採購
                </button>
            @endif
            <button type="button" onclick="location.href='{{ route('in.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}
@endsection

@section('script')
    @include('purchase.in._script')
@endsection
