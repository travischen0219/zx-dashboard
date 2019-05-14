@extends('layouts.app')

@section('title','入庫')

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
<div id="loader"></div>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">

    <!-- BEGIN THEME PANEL -->
    @include('layouts.theme_panel')    
    <!-- END THEME PANEL -->


    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 入庫
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
     <form role="form" action="{{ route('stock.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 操作 :</label>
                        <div class="col-md-9">
                            <select class="form-control" style="font-size: 14px;" name="search_category">

                                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>一般入庫</option>
                                {{-- <option value="2" {{$search_code == 2 ? 'selected' : ''}}>庫存調整</option> --}}
                                <option value="3" {{$search_code == 3 ? 'selected' : ''}}>起始庫存</option>
                                <option value="4" {{$search_code == 4 ? 'selected' : ''}}>採購轉入庫</option>
                                <option value="5" {{$search_code == 5 ? 'selected' : ''}}>退貨入庫</option>
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
                    <a href="{{ route('stock.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增入庫</a>
                </div>
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                {{ $group = '' }}
                {{ $number = '' }}
                
                <table class="table table-striped table-bordered table-hover" id="sample_3" >
                    <thead>
                        <tr>
                            <th>入庫日期</th>
                            <th>批號</th>
                            <th>操作</th>
                            <th>物料</th>
                            <th>倉儲</th>
                            <th>數量</th>
                            <th>入庫 (前->後) 數量</th>
                            <th>備註</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($stocks as $stock)
                            <tr>
                                <td>{{ $stock->stock_date }}</td>

                                <td @if($group == '')
                                        {{$number = $stock->lot_number}}
                                        {{$group = 1}}
                                        style='background-color:lightgreen;'
                                    @elseif($group == 1)
                                        @if($number == $stock->lot_number)
                                            style='background-color:lightgreen;'
                                        @else
                                            {{$group = 2}}
                                            {{$number = $stock->lot_number}}
                                            style='background-color:lightblue;'     
                                        @endif
                                        
                                    @elseif($group == 2)
                                        @if($number == $stock->lot_number)
                                            style='background-color:lightblue;'     
                                        @else
                                            {{$group = 1}}
                                            {{$number = $stock->lot_number}}
                                            style='background-color:lightgreen;'
                                        @endif
                                    @endif
                                    )>{{ $stock->lot_number }}
                                </td>

                                <td>
                                    @if($stock->stock_option == 1)
                                        <span style="color:blue;">一般入庫</span>
                                    @elseif($stock->stock_option == 2)
                                        {{-- <span style="color:red;">庫存調整</span>  --}}
                                    @elseif($stock->stock_option == 3)
                                        <span style="color:green;">起始庫存</span>
                                    @elseif($stock->stock_option == 4)
                                        <span style="color:purple;">採購轉入庫</span>
                                    @elseif($stock->stock_option == 5)
                                        <span style="color:red;">退貨入庫</span>
                                    @endif
                                </td>
                                <td>{{ $stock->material_name->fullName }}</td>
                                <td>
                                    @if($stock->status > 0)
                                        <a href="{{ route('stock.edit', $stock->id) }}" class="btn blue btn-outline btn-sm">編輯入庫單</a>
                                    @else
                                        {{$stock->warehouse_name->code}}
                                    @endif
                                </td>
                                <td align="right">
                                    @if($stock->quantity < 0) 
                                        <span style="color:red;">{{ $stock->quantity }}</span> 
                                    @else
                                        {{ $stock->quantity }}
                                    @endif
                                </td>
                                <td align="center">{{ $stock->calculate_quantity }}</td>
                                <td> {{ $stock->memo }} </td>
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

$(window).load(function() {
    $("#loader").fadeOut("slow");
});
</script>
@endsection