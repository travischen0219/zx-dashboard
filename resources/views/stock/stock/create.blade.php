@extends('b4.app')

@section('title', $title)
@section('page-header')
    <i class="fas fa-archive active-color mr-2"></i> 庫存 - 新增{{ $title }}
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
        'url' => route('stock.store'),
        'class' => 'form mb-5',
        'id' => 'app'
    ]) !!}
        @include('stock.stock._form')

        <div class="form-group mt-3 text-center">
            <button type="submit" class="btn btn-primary px-5">
                <i class="fas fa-check mr-1"></i> 新增{{ $title }}
            </button>
            <button type="button" onclick="location.href='{{ route('stock.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}
@endsection

@section('script')
    @include('stock.stock._script')

    <script>
    $('label').addClass('{{ $text }}')
    </script>
@endsection
