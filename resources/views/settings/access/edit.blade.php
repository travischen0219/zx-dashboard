@extends('b4.app')

@section('title', '修改權限角色')
@section('page-header')
    <i class="fas fa-building active-color mr-1"></i>基本資料 - 修改權限角色
@endsection

@section('css')
<style>
    .form label {
        width: 125px;
    }
</style>
@endsection

@section('content')

    {!! Form::open([
        'url' => route('access.update', $access->id),
        'method' => 'PUT',
        'class' => 'form'
    ]) !!}
        @include('settings.access._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">修改權限角色</button>
            <button type="button" onclick="location.href='{{ route('access.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')

@endsection
