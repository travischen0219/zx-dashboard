@extends('layouts.app')

@section('title','採購')

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

    #loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url("{{asset('assets/apps/img/loader_icon.gif')}}") 50% 50% no-repeat rgb(249,249,249);
        background-size:120px 120px;
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
    <h1 class="page-title"> 採購
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')
<div id="loader"></div>

<div class="row">
    <form role="form" action="{{ route('buy.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 狀態 :</label>
                        <div class="col-md-9">
                            <select class="form-control" style="font-size: 14px;" name="search_category">

                                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>未採購</option>
                                <option value="2" {{$search_code == 2 ? 'selected' : ''}}>已採購</option>
                                <option value="3" {{$search_code == 3 ? 'selected' : ''}}>已到貨</option>
                                <option value="11" {{$search_code == 11 ? 'selected' : ''}}>轉半成品</option>
                                <option value="4" {{$search_code == 4 ? 'selected' : ''}}>已轉到入庫</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center">批號 :</label>
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
                    <a href="{{ route('buy.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增採購</a>
                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="caption font-dark" style="margin-left:5px">
                    <span class="btn btn-primary" onclick="pdfsubmit();"><i class="fa fa-print"></i> 多筆PDF列印</span>
                    全選 <input type="checkbox" class="checkAll" id="checkAll"  value="1">
                </div>
                
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_3" >
                    <thead>
                        <tr>
                            <th>單號</th>
                            <th>PDF列印</th>
                            <th>批號</th>
                            <th>供應商</th>
                            <th>說明</th>
                            <th>採購日期</th>
                            <th>預計到貨日</th>
                            <th>實際到貨日</th>
                            <th>狀態</th>
                            <th>操 作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($buys as $buy)

                            <tr>
                                
                                <td>P{{$buy->buy_no}}</td>
                                <td><a href="/pdf/?id={{$buy->id}}" target="_blank" class="btn blue btn-outline btn-sm">列印</a>&nbsp;&nbsp;
<input type="checkbox" class="print_pdf" name="print_pdf"  value="{{$buy->id}}">
                                </td>
                                <td>{{$buy->lot_number}}</td>                              
                                <td>{{$buy->supplier_name->shortName}}</td>
                                <td>{{$buy->memo}}</td>
                                <td>{{$buy->buyDate}}</td>
                                <td>{{$buy->expectedReceiveDate}}</td>
                                <td>{{$buy->realReceiveDate}}</td>
                                <td>
                                    @if($buy->status == '1')
                                        @if($buy->status_return == 1 || $buy->status_return == 2)
                                            <span style="color:red">未採購<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-form-{{$buy->id}}').submit();"> (退貨)</a></span>  
                                                <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form>
                                        @elseif($buy->status_exchange == 1 || $buy->status_exchange == 2)
                                            <span style="color:red">未採購<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>  
                                                <form id="search-exchange-form-{{$buy->id}}" action="{{ route('p_exchange.search_exchange') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form> 
                                        @else
                                            <span style="color:red">未採購</span> 
                                        @endif

                                    @elseif($buy->status == '2')
                                        @if($buy->status_exchange == 1 || $buy->status_exchange == 2)
                                            <span style="color:blue">已採購<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>  
                                                <form id="search-exchange-form-{{$buy->id}}" action="{{ route('p_exchange.search_exchange') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form> 
                                        @elseif($buy->status_return == 1 || $buy->status_return == 2)
                                            <span style="color:blue">已採購<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-form-{{$buy->id}}').submit();"> (退貨)</a></span>  
                                                <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form>
                                        @else
                                            <span style="color:blue">已採購</span>
                                        @endif
                                        
                                    @elseif($buy->status == '3') 
                                        @if($buy->status_exchange == 1 || $buy->status_exchange == 2)
                                            <span style="color:purple">已到貨<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>  
                                                <form id="search-exchange-form-{{$buy->id}}" action="{{ route('p_exchange.search_exchange') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form> 
                                        @elseif($buy->status_return == 1 || $buy->status_return == 2)
                                            <span style="color:purple">已到貨<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-form-{{$buy->id}}').submit();"> (退貨)</a></span>  
                                                <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form> 
                                        @else
                                            <span style="color:purple">已到貨</span> 
                                        @endif

                                    @elseif($buy->status == '11') 
                                        @if($buy->status_exchange == 1 || $buy->status_exchange == 2)
                                            <span style="color:#248ff1">轉半成品<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>  
                                                <form id="search-exchange-form-{{$buy->id}}" action="{{ route('p_exchange.search_exchange') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form> 
                                        @elseif($buy->status_return == 1 || $buy->status_return == 2)
                                            <span style="color:#248ff1">轉半成品<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-form-{{$buy->id}}').submit();"> (退貨)</a></span>  
                                                <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form> 
                                        @else
                                            <span style="color: #248ff1">轉半成品</span>  
                                        @endif
                                        
                                    @elseif($buy->status == '4') 
                                        
                                        @if($buy->status_exchange == 1 || $buy->status_exchange == 2)
                                            <span style="color:green">已轉到入庫<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-exchange-form-{{$buy->id}}').submit();"> (換貨)</a></span>  
                                                <form id="search-exchange-form-{{$buy->id}}" action="{{ route('p_exchange.search_exchange') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form> 
                                        @elseif($buy->status_return == 1 || $buy->status_return == 2)
                                            <span style="color:green">已轉到入庫<a href="javascript:;" style="color:red;" onclick="event.preventDefault();
                                                document.getElementById('search-form-{{$buy->id}}').submit();"> (退貨)</a></span>  
                                                <form id="search-form-{{$buy->id}}" action="{{ route('p_sales_return.search_return') }}" method="post" style="display:none">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="buy_id" value="{{$buy->id}}">
                                                </form> 
                                        @else
                                            <span style="color:green">已轉到入庫</span>  
                                        @endif
                                    @endif
                                </td>
                                <td align="center" id="functions_btn">

                                    
                                    @if($buy->status == 1 || $buy->status == 2 || $buy->status == 3 || $buy->status == 11)
                                        <a href="{{ route('buy.edit', $buy->id) }}" class="btn blue btn-outline btn-sm">修改</a>
                                        <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                                            if(confirm('確定要刪除嗎 ?')){
                                                event.preventDefault();
                                                document.getElementById('delete-form-{{$buy->id}}').submit();
                                            } else {
                                                event.preventDefault();
                                            }">刪除</a>
                                        <form id="delete-form-{{$buy->id}}" action="{{ route('buy.destroy', $buy->id) }}" method="post" style="display:none">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                    @elseif($buy->status == 4)
                                        <a href="{{ route('buy.edit', $buy->id) }}" class="btn purple btn-outline btn-sm">查看</a>
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


$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});

function pdfsubmit()
{
           
            var chkArray = [];
          
            $(".print_pdf:checked").each(function() {
                chkArray.push($(this).val());
            });
            
            /* we join the array separated by the comma */
            var selected;
            selected = chkArray.join(',');
            openInNewTab("/pdf/?id="+selected);
            
        
    
}

function openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}

$(window).load(function() {
    $("#loader").fadeOut("slow");
});
</script>
@endsection