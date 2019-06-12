@extends('b4.app')

@section('title','員工資料')
@section('page-header','基本資料 - 員工資料')

@section('content')

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark">
                    <a href="{{ route('staff.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增人員</a>
                </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">

            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->

    </div>
</div>

    <table class="table table-striped table-bordered table-hover" id="data" >
        <thead>
            <tr>
                <th>編 號</th>
                <th>姓 名</th>
                <th>部 門</th>
                <th>職 稱</th>
                <th>電 話</th>
                <th>Email</th>
                <th>狀 態</th>
                <th>操 作</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
                @if(true)
                <tr>

                    <td>{{$user->staff_code}}</td>
                    <td>{{$user->fullname}}</td>
                    <td>
                        @if($user->department_id == 0)
                            <span style="color:red">未指派</span>
                        @else
                            {{$user->department_name->name}}
                        @endif
                    </td>
                    <td>
                        @if($user->professional_title_id == 0)
                            <span style="color:red">未指派</span>
                        @else
                            {{$user->professional_title_name->name}}
                        @endif
                    </td>
                    <td>{{$user->mobile}}</td>
                    <td>{{$user->email}}</td>
                    <td> @if($user->status == 1)
                            <span style="color:blue">啟用</span>
                            @elseif($user->status == 2)
                            <span style="color:red">關閉</span>
                            @endif
                    </td>
                    <td  align="center" id="functions_btn"><a href="{{ route('staff.edit', $user->id) }}" class="btn blue btn-outline btn-sm">修改</a>
                        <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                            if(confirm('確定要刪除嗎 ?')){
                                event.preventDefault();
                                document.getElementById('delete-form-{{$user->id}}').submit();
                            } else {
                                event.preventDefault();
                            }">刪除</a></td>
                        <form id="delete-form-{{$user->id}}" action="{{ route('staff.destroy', $user->id) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                        </form>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

@endsection

@section('script')
    <script>
    $(function () {
        $('#data').DataTable({
            "language": {
                "url": '/json/datatable.zh-tw.json'
            }
        })
    })
    </script>
@endsection
