@extends('layouts.app')

@section('title','餘料處理')

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
    <h1 class="page-title"> 餘料處理
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
     <form role="form" action="{{ route('residual_material_processing.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center">名稱 :</label>
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
                    <a href="{{ route('residual_material_processing.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增餘料處理</a>
                </div>
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_3" >
                    <thead>
                        <tr>
                            <th>處理日期</th>
                            <th>名稱</th>

                            <th>物料</th>
                            <th>倉儲</th>
                            <th>數量</th>
                            <th>處理(前->後)數量</th>
                            <th>備註</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($stocks as $stock)

                            <tr>
                                <td>{{ $stock->stock_date }}</td>
                                <td>{{ $stock->lot_number }}</td>
                                
                                <td>{{ $stock->material_name->fullName }}</td>
                                <td>{{ $stock->warehouse_name->code }}</td>
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


</script>
@endsection