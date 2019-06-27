@extends('b4.app')

@section('title', '批號管理')
@section('page-header')
    <i class="fas fa-puzzle-piece active-color mr-1"></i>基本資料 - 批號管理
    <small class="text-muted">批號建立與編輯</small>
@endsection

@section('content')
    {!! Form::open([
        'url' => 'lot/store',
        'class' => 'form form-inline'
    ]) !!}

        @include('lot._form')

    {!! Form::close() !!}
@endsection
