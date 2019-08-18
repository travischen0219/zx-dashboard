@extends('b4.app')

@section('title', '物料分類修改')
@section('page-header')
    <i class="fab fa-deviantart active-color mr-1"></i>基本資料 - 物料分類修改
@endsection

@section('css')

@endsection

@section('content')

    {!! Form::open([
        'url' => route('material_category.update', $cate->id),
        'method' => 'PUT',
        'class' => 'form'
    ]) !!}
        @include('settings.material_category._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">修改物料分類</button>
            <button type="button" onclick="location.href='{{ route('material_category.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')
    @include('b4.alert')
@endsection
