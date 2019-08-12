@extends('b4.app')

@section('title', '員工資料建立')
@section('page-header')
    <i class="fas fa-tasks active-color mr-1"></i>基本資料 - 員工資料建立
@endsection

@section('css')
    <style>
    </style>
@endsection

@section('content')

    {!! Form::open([
        'url' => route('staff.store'),
        'class' => 'form'
    ]) !!}
        @include('settings.staff._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">新增人員
            </button>
            <button type="button" onclick="location.href='{{ route('staff.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')

@endsection
