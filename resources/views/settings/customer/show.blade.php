@extends('layouts.app')

@section('title','客戶')

@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />

<style>
    a{
        text-decoration:none;
    }
    #sample_1 td{
        font-size: 16px;
        vertical-align:middle;
    }
    #sample_1 th{
        font-size: 16px;
        vertical-align:middle;
    }
    #functions_btn{
        text-align: center;
    }
    table thead{
        color:#fff;
        background-color: #248ff1;
    }
    #sample_1_filter input { 
        width:300px !important;
    }
    #popup{
    width:400px;
    height:160px;
    display:block; 
    background-color: white;
    margin:auto;
    }
    #popup p{
        padding-top:20px;
        display:block; 
        text-align:center;
    }
    #popup img{
        display:block; 
        margin:0 auto ;
        padding:0px 20px;
    }
</style>

@endsection

@section('page_header')
<!-- BEGIN PAGE HEADER-->

<!-- BEGIN PAGE BAR -->
<div class="page-bar">

    <!-- BEGIN THEME PANEL -->
    @include('layouts.theme_panel')    
    <!-- END THEME PANEL -->


    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 客戶
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')




<div class="row">
    <div class="col-md-12" >
        <form role="form" action="{{ route('customers.search') }}" method="POST" id="search_from">
            {{ csrf_field() }}
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 56px;padding-top: 20px;">
                <div class="col-md-5">
                    <div class="form-group">
                            <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 篩選分類 :</label>
                            <div class="col-md-9">
                                <select class="form-control" style="font-size: 14px;" name="search_category" onchange="search();">

                                    <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                    <option value="1" {{ $search_code == 1 ? 'selected' : '' }}>北部</option>
                                    <option value="2" {{ $search_code == 2 ? 'selected' : '' }}>中部</option>
                                    <option value="3" {{ $search_code == 3 ? 'selected' : '' }}>南部</option>
                                    <option value="4" {{ $search_code == 4 ? 'selected' : '' }}>海外</option>
                                    <option value="5" {{ $search_code == 5 ? 'selected' : '' }}>中國大陸</option>
                                  
                                </select>
                            </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        {{-- <button type="submit" class="btn" style="background-color: #248ff1;color:#fff;font-size: 14px;">篩選</button> --}}
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark">
                    <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增客戶</a>
                </div>
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    <thead>
                        <tr>
                            <th>編 號</th>
                            <th>分 類</th>
                            <th>全 名</th>
                            <th>電 話</th>
                            <th>地 址</th>
                            <th>操 作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($customers as $customer)
                            @if(true)
                            <tr>
                                
                                <td>{{$customer->code}}</td>
                                <td>

                                    @if($customer->category == '')
                                        未設定
                                    @elseif($customer->category == 1)                                    
                                        常用
                                    @elseif($customer->category == 2)
                                        不常用
                                    @endif
                                </td>
                                
                                <td><a href="{{ route('customers.show', $customer->id) }}">{{$customer->fullName}}</a></td>
                                <td>{{$customer->tel}}</td>
                                <td>{{$customer->address}}</td>
                                <td align="center" id="functions_btn">
                                    {{-- <a href="{{ route('customers.show', $customer->id) }}" class="btn purple btn-outline btn-sm">查看</a>                                 --}}
                                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn blue btn-outline btn-sm">修改</a>
                                    <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                                        if(confirm('確定要刪除嗎 ?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$customer->id}}').submit();
                                        } else {
                                            event.preventDefault();
                                        }">刪除</a>
                                    
                                    <form id="delete-form-{{$customer->id}}" action="{{ route('customers.destroy', $customer->id) }}" method="post" style="display:none">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form>
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->

    </div>
</div>


@endsection

@section('scripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{asset('assets/global/scripts/datatable.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="{{asset('assets/pages/scripts/table-datatables-buttons.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script src="{{asset('assets/apps/scripts/jquery.magnific-popup.js')}}" type="text/javascript"></script>

<script>
function search(){
    $("#search_from").submit();
}

</script>
@endsection