@extends('layouts.app')

@section('title','成本、利潤估算')

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
    <h1 class="page-title"> 成本、利潤估算
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
     <form role="form" action="{{ route('prime_cost.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    {{-- <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#8781d2;font-size: 16px;line-height: 32px;text-align: center"> 狀態 :</label>
                        <div class="col-md-9">
                           
                        </div>
                    </div> --}}
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
    
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
               
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_3" >
                    <thead>
                        <tr>
                            <th>單號</th>
                            <th>批號</th>
                            <th>客戶名稱</th>
                            <th>銷貨單完成日期</th>
                            <th>估算狀態</th>
                            <th>成本利潤</th>
                            <th>備註</th>
                            <th>操 作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                         @foreach($sales as $sale)

                            <tr>
                                
                                <td>S{{$sale->sale_no}}</td>  
                                <td>{{$sale->lot_number}}</td>                            
                                <td>{{$sale->customer_name->shortName}}</td>
                                <td>{{$sale->receiveDate}}</td>
                                <td>
                                    @if($sale->status_profit != 2)
                                        <span style="color:red">編輯中</span>
                                    @elseif($sale->status_profit == 2)
                                        <span style="color:blue">已完成</span>  
                                    @endif
                                </td>
                                <td>
                                    @if($sale->profit == null)
                                        <span style="color:purple">未確認成本</span>
                                    @elseif($sale->profit > 0)
                                        <span style="color:blue">{{ $sale->profit }}</span>
                                    @elseif($sale->profit <= 0)
                                        <span style="color:red">{{ $sale->profit }}</span>                                        
                                    @endif
                                </td>
                                <td>{{$sale->memo}}</td>
                                <td align="center" id="functions_btn">
                                    @if($sale->status_profit != 2)
                                        <a href="{{ route('prime_cost.edit', $sale->id) }}" class="btn blue btn-outline btn-sm">編輯</a>                                        
                                    @elseif($sale->status_profit == 2)
                                        <a href="{{ route('prime_cost.show', $sale->id) }}" class="btn purple btn-outline btn-sm">查看</a>
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

                                    <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    
                                                    <span> &nbsp;&nbsp;Loading... </span>
                                                </div>
                                            </div>
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
           
            var chkArray = [];
          
            $(".print_pdf:checked").each(function() {
                chkArray.push($(this).val());
            });
            
            /* we join the array separated by the comma */
            var selected;
            selected = chkArray.join(',');
            openInNewTab("/pdf/sale.php?id="+selected);
            
        
    
}

function openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}

</script>
@endsection