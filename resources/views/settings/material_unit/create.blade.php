@extends('b4.app')

@section('title', '新增單位')
@section('page-header')
    <i class="fas fa-ruler active-color mr-1"></i>基本資料 - 新增單位
@endsection

@section('css')
    <style>
    </style>
@endsection

@section('content')

    {!! Form::open([
        'url' => route('material_unit.store'),
        'class' => 'form'
    ]) !!}
        @include('settings.material_unit._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">新增單位
            </button>
            <button type="button" onclick="location.href='{{ route('material_unit.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')
    @include('b4.alert')
@endsection
