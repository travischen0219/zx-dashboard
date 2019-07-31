@extends('b4.app')

@section('title', $title)
@section('page-header')
    <i class="fas fa-tasks active-color mr-1"></i> {{ $title }}
@endsection

@section('css')

@endsection

@section('content')

    {!! Form::open([
        'url' => route('inventory.update', $inventory->id),
        'method' => 'PUT',
        'class' => 'form'
    ]) !!}

        @include('stock.inventory._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">修改盤點</button>
            <button type="button" onclick="location.href='{{ route('inventory.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>

    {!! Form::close() !!}
@endsection

@section('script')
    @include('stock.inventory._script')
@endsection
