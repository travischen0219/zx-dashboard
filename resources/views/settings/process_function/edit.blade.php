@extends('b4.app')

@section('title', '加工方式修改')
@section('page-header')
    <i class="fas fa-id-card-alt active-color mr-1"></i>基本資料 - 加工方式修改
@endsection

@section('css')

@endsection

@section('content')

    {!! Form::open([
        'url' => route('process_function.update', $unit->id),
        'method' => 'PUT',
        'class' => 'form'
    ]) !!}
        @include('settings.process_function._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">修改加工方式</button>
            <button type="button" onclick="location.href='{{ route('process_function.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}

@endsection


@section('script')
    @include('b4.alert')
@endsection
