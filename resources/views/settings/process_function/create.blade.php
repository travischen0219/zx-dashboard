@extends('b4.app')

@section('title', '新增加工方式')
@section('page-header')
    <i class="fas fa-spray-can active-color mr-1"></i>基本資料 - 新增加工方式
@endsection

@section('css')
    <style>
    </style>
@endsection

@section('content')

    {!! Form::open([
        'url' => route('process_function.store'),
        'class' => 'form'
    ]) !!}
        @include('settings.process_function._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">新增加工方式
            </button>
            <button type="button" onclick="location.href='{{ route('process_function.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')
    @include('b4.alert')
@endsection
