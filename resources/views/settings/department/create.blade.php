@extends('b4.app')

@section('title', '部門資料建立')
@section('page-header')
    <i class="fas fa-building active-color mr-1"></i>基本資料 - 部門資料建立
@endsection

@section('css')

@endsection

@section('content')

    {!! Form::open([
        'url' => route('department.store'),
        'class' => 'form'
    ]) !!}
        @include('settings.department._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">新增部門</button>
            <button type="button" onclick="location.href='{{ route('department.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')

@endsection
