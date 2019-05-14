@extends('layouts.app')

@section('title','集貨撿貨')

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
    <h1 class="page-title"> 集貨撿貨
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
   
        {{ csrf_field() }}
        
   
    

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark">
                <span class="btn" style="background-color: #248ff1;color:#fff;font-size: 16px;" onclick="pdfsubmit();">產生集貨撿貨單 </span>
                </div>
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_3" >
                    <thead>
                        <tr>
                            <th>單號</th>
                            <th>批號</th>
                            <th>客戶名稱</th>
                            <th>申請日期</th>
                            <th>有效期限</th>
                            <th>通過日期</th>
                            <th>狀態</th>
                            <th>備註</th>
                           
                        </tr>
                    </thead>
                    
                    <tbody>
                         @foreach($applies as $apply)

                            <tr>
                                
                                <td>A{{$apply->apply_no}}</td>                            
                                <td>{{$apply->lot_number}}</td>                            
                                <td>{{$apply->customer_name->shortName}}</td>
                                <td>{{$apply->applyDate}}</td>
                                <td>{{$apply->expireDate}}</td>
                                <td>{{$apply->receiveDate}}</td>
                                <td>
                                    @if($apply->status == '1') 
                                    <span style="color:red">申請中</span> 
                                    @elseif($apply->status == '2') 
                                    <span style="color:blue">已通過</span>
                                    @endif
                                </td>
                                <td>{{$apply->memo}}</td>
                                
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

function pdfsubmit()
{
           
           
            
           
           // var selected;
            //selected = $( "#monthly option:selected" ).text();
            
            openInNewTab("/pdf/picking.php");
            
        
    
}

function openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}


</script>
@endsection