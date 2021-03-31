@extends('b4.app')

@section('title', '新增權限角色')
@section('page-header')
    <i class="fas fa-key active-color mr-1"></i>基本資料 - 新增權限角色
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
        'url' => route('access.store'),
        'class' => 'form'
    ]) !!}
        @include('settings.access._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">新增權限角色</button>
            <button type="button" onclick="location.href='{{ route('access.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')

@endsection
