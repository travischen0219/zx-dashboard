@extends('b4.app')

@section('title', '職稱修改')
@section('page-header')
    <i class="fas fa-id-card-alt active-color mr-1"></i>基本資料 - 職稱修改
@endsection

@section('css')

@endsection

@section('content')

    {!! Form::open([
        'url' => route('professional_title.update', $pro_title->id),
        'method' => 'PUT',
        'class' => 'form'
    ]) !!}
        @include('settings.professional_title._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">修改職稱</button>
            <button type="button" onclick="location.href='{{ route('professional_title.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')

@endsection
