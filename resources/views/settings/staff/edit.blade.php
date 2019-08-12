@extends('b4.app')

@section('title', '員工資料修改')
@section('page-header')
    <i class="fas fa-tasks active-color mr-1"></i>基本資料 - 員工資料修改
@endsection

@section('css')
    <style>
    </style>
@endsection

@section('content')

    {!! Form::open([
        'url' => route('staff.update', $user->id),
        'method' => 'PUT',
        'class' => 'form'
    ]) !!}
        @include('settings.staff._form')

        <div class="form-group">
            <label></label>

            <button type="submit" class="btn btn-primary">修改人員
            </button>
            <button type="button" onclick="location.href='{{ route('staff.index') }}'" class="btn btn-link ml-3">取消</button>
        </div>
    {!! Form::close() !!}
@endsection


@section('script')
<script>

</script>
@endsection
