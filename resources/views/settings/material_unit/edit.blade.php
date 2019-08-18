@extends('b4.app')

@section('title', '單位修改')
@section('page-header')
    <i class="fas fa-id-card-alt active-color mr-1"></i>基本資料 - 單位修改
@endsection

@section('css')

@endsection

@section('content')

    {!! Form::open([
        'url' => route('material_unit.update', $unit->id),
        'method' => 'PUT',
        'class' => 'form'
    ]) !!}
        @include('settings.material_unit._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">修改單位</button>
            <button type="button" onclick="location.href='{{ route('material_unit.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')
    @include('b4.alert')
@endsection
