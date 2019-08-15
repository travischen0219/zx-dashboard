@extends('b4.app')

@section('title', '職稱建立')
@section('page-header')
    <i class="fas fa-id-card-alt active-color mr-1"></i>基本資料 - 職稱建立
@endsection

@section('css')
    <style>
    </style>
@endsection

@section('content')

    {!! Form::open([
        'url' => route('professional_title.store'),
        'class' => 'form'
    ]) !!}
        @include('settings.professional_title._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">新增職稱
            </button>
            <button type="button" onclick="location.href='{{ route('professional_title.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')

@endsection
