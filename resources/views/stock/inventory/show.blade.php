@extends('layouts.app')

@section('title','盤點')

@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<style>
    a{
        text-decoration:none;
    }
    #sample_3 td{
        font-size: 16px;
        vertical-align:middle;
    }
    #sample_3 th{
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
    #sample_3_filter input { 
        width:300px !important;
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
    <h1 class="page-title"> 盤點
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
    <form role="form" action="{{ route('inventory.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 狀態 :</label>
                        <div class="col-md-9">
                            <select class="form-control" style="font-size: 14px;" name="search_category">

                                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>盤點中</option>
                                <option value="2" {{$search_code == 2 ? 'selected' : ''}}>已盤點</option>
                                
                            </select>
                        </div>
                    </div>
                     <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center">盤點名稱 :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="search_lot_number" id="search_lot_number">
                        </div>
                    </div> 
                    
                    <div class="col-md-2">
                        <button type="submit" class="btn" style="background-color: #248ff1;color:#fff;font-size: 16px;">搜 尋</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark">
                    <a href="{{ route('inventory.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增盤點</a>
                </div>
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_3" >
                    <thead>
                        <tr>
                            <th>盤點單號</th>
                            <th>盤點名稱</th>
                            <th>說明</th>
                            <th>開始日期</th>
                            <th>結束日期</th>
                            <th>倉庫分類</th>
                            <th>物料盤點紀錄</th>
                            <th>狀態</th>
                            <th>操 作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($inventories as $inventory)

                            <tr>
                                <td>{{$inventory->inventory_no}}</td>                                
                                <td>{{$inventory->lot_number}}</td>                                
                                <td>{{$inventory->memo}}</td>
                                <td>{{$inventory->inventory_sdate}}</td>
                                <td>{{$inventory->inventory_edate}}</td>
                                <td>
                                    @if($inventory->warehouse_category == 0)
                                        全部
                                    @else
                                        {{$inventory->warehouse_category_name->name}}
                                    @endif
                                </td>
                                <td align="center">
                                    @if($inventory->status == '1') 
                                        <a href="{{ url('stock/inventory/edit_list/'.$inventory->id) }}" class="btn green btn-outline btn-sm">盤點</a> 
                                    @elseif($inventory->status == '2') 
                                        <a href="{{ url('stock/inventory/show_list/'.$inventory->id) }}" class="btn purple btn-outline btn-sm">查看</a>
                                    @endif
                                </td>
                                <td>
                                    @if($inventory->status == '1') <span style="color:red">盤點中</span> 
                                    @elseif($inventory->status == '2') <span style="color:green">已盤點</span> 
                                    @endif
                                </td>
                                <td align="center" id="functions_btn">
                                    @if($inventory->status == 1)
                                        <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn blue btn-outline btn-sm">修改</a>
                                        <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                                        if(confirm('確定要刪除嗎 ?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$inventory->id}}').submit();
                                        } else {
                                            event.preventDefault();
                                        }">刪除</a>
                                    @elseif($inventory->status == 2)
                                        <a href="{{ route('inventory.show', $inventory->id) }}" class="btn purple btn-outline btn-sm">查看</a>
                                    @endif
                                    
                                    <form id="delete-form-{{$inventory->id}}" action="{{ route('inventory.destroy', $inventory->id) }}" method="post" style="display:none">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form>
                                </td>
                            </tr>

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

<script>


</script>
@endsection