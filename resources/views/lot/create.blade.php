@extends('b4.app')

@section('title', '批號管理')
@section('page-header')
    <i class="fas fa-puzzle-piece active-color mr-1"></i>基本資料 - 批號建立
@endsection

@section('css')
    <style>

    </style>
@endsection

@section('content')
    {!! Form::open([
        'url' => 'lot/store',
        'method' => 'put',
        'class' => 'form'
    ]) !!}
        @include('lot._form')
    {!! Form::close() !!}
@endsection

@section('script')
    @include('lot._script')
@endsection
