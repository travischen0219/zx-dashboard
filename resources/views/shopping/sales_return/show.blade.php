@extends('layouts.app')

@section('title','銷貨退貨')

@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />

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
        background-color: #8781d2;
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
    <h1 class="page-title"> 銷貨退貨
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
     <form role="form" action="{{ route('s_sales_return.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#8781d2;font-size: 16px;line-height: 32px;text-align: center"> 狀態 :</label>
                        <div class="col-md-9">
                            <select class="form-control" style="font-size: 14px;" name="search_category">
                                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>退貨中</option>
                                <option value="2" {{$search_code == 2 ? 'selected' : ''}}>退貨完成</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#8781d2;font-size: 16px;line-height: 32px;text-align: center">批號 :</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="search_lot_number" id="search_lot_number">
                        </div>
                    </div> 
                    <div class="col-md-2">
                        <button type="submit" class="btn" style="background-color: #8781d2;color:#fff;font-size: 16px;">搜 尋</button>
                    </div>
                </div>
            </div>
        </div>
    </form> 
    <form role="form" action="{{ route('s_sales_return.create') }}" method="GET" id="create_from">
        <div class="col-md-12" style="margin-top: 20px;">
            <div class="col-md-5">
                <label class="col-md-3 control-label" style="color:red;font-size: 16px;line-height: 32px;text-align: center">銷貨單號 :</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="sale_no" id="sale_no" value="{{ old('sale_no') }}">
                </div>
            </div> 
            <div class="col-md-2">
                <button type="button" onclick="submit_btn();" class="btn btn-primary" style=""><i class="fa fa-plus"></i> 新增退貨</button>
            </div>
        </div>
    </form>
    
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark">
                    {{-- <a href="{{ route('sales_return.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增退貨</a> --}}
                </div>
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_3" >
                    <thead>
                        <tr>
                            <th>銷貨單號</th>
                            <th>批號</th>
                            <th>客戶名稱</th>
                            <th>退貨日期</th>
                            <th>退貨完成日</th>
                            <th>銷貨單狀態</th>                            
                            <th>退貨狀態</th>
                            <th>操 作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                         @foreach($sales as $sales_return)

                            <tr>
                                
                                <td>S{{$sales_return->sale_no}}</td>                            
                                <td>{{$sales_return->lot_number}}</td>                            
                                <td>{{$sales_return->customer_name->shortName}}</td>
                                <td>{{$sales_return->returnDate}}</td>
                                <td>{{$sales_return->realReturnDate}}</td>
                                <td>
                                    @if($sales_return->sale_name->status == '1') 
                                        <span style="color:red">新訂單</span> 
                                        @elseif($sales_return->sale_name->status == '2') 
                                        <span style="color:blue">已完成</span>
                                    @endif
                                </td>
                                <td>
                                    @if($sales_return->status == '1') 
                                        <span style="color:red">退貨中</span> 
                                    @elseif($sales_return->status == '2') 
                                        <span style="color:blue">退貨完成</span>
                                    @endif
                                </td>
                                <td align="center" id="functions_btn">
                                    @if($sales_return->status == 1)
                                        <a href="{{ route('s_sales_return.edit', $sales_return->id) }}" class="btn blue btn-outline btn-sm">修改</a>
                                        <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                                        if(confirm('確定要刪除嗎 ?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$sales_return->id}}').submit();
                                        } else {
                                            event.preventDefault();
                                        }">刪除</a>
                                    <form id="delete-form-{{$sales_return->id}}" action="{{ route('s_sales_return.destroy', $sales_return->id) }}" method="post" style="display:none">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form>
                                    @elseif($sales_return->status == 2)
                                        <a href="{{ route('s_sales_return.show', $sales_return->id) }}" class="btn purple btn-outline btn-sm">查看</a>
                                    @endif
                                    
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
<button id="error_sale_no_prefix" class="btn btn-danger mt-sweetalert" data-title=" 銷貨單號開頭為 S" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_sale_no" class="btn btn-danger mt-sweetalert" data-title=" 銷貨單號長度為12個字" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>

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
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/pages/scripts/ui-sweetalert.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
function submit_btn(){
    if($('#sale_no').val().substr(0,1) != 'S'){
        if($('#sale_no').val().substr(0,1) != 's'){
            $('#error_sale_no_prefix').click();
            return;
        }
    }
    if($('#sale_no').val().trim().length != 12){
        $('#error_sale_no').click();
        return;
    }
    $("#create_from").submit();
}

</script>
@endsection