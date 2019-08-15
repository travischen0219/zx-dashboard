@extends('b4.app')

@section('title', '部門資料修改')
@section('page-header')
    <i class="fas fa-building active-color mr-1"></i>基本資料 - 部門資料修改
@endsection

@section('css')

@endsection

@section('content')

    {!! Form::open([
        'url' => route('department.update', $dep->id),
        'method' => 'PUT',
        'class' => 'form'
    ]) !!}
        @include('settings.department._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">修改部門</button>
            <button type="button" onclick="location.href='{{ route('department.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')

@endsection
