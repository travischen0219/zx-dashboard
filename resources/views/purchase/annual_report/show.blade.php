@extends('layouts.app')

@section('title','採購年報表')

@section('css')

<link href="{{asset('assets/global/plugins/jquery-ui/jquery-ui.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />

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
    <h1 class="page-title"> 採購年報表
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
   

        {{-- <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    
                </div>
            </div>
        </div> --}}
   

        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet light">

                    @include('includes.messages')
                   

    
                
                    
                <div class="portlet-body">
                    <div class="col-md-12" style="line-height: 80px;">
                        <span style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 起始日期 : </span>
                        <input type="date" name="startDate" id="startDate" style="height: 32px; vertical-align: middle;">
                    </div>
                    <div class="col-md-12" style="line-height: 80px;">
                        <span style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 結束日期 : </span>
                        <input type="date" name="endDate" id="endDate" style="height: 32px; vertical-align: middle;">
                    </div>
                    <div class="col-md-12" style="line-height: 80px;">
                        <span style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 批號 : </span>
                        <input type="text" name="lot_number" id="lot_number" style="height: 32px; vertical-align: middle;">
                    </div>
                    <div class="col-md-12" style="line-height: 80px;">
                        <span style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 供應商 : </span>
                        <button id="select_supplier" type="button" class="btn blue" onclick="selectSupplier();">按此選擇 (不選表示選擇全部)</button>
                        <input type="hidden" id="supplier" name="supplier" value="all">
                    </div>
                    <div class="col-md-12" style="line-height: 80px;">
                        <span class="btn" style="background-color: #248ff1;color:#fff;font-size: 16px;" onclick="pdfsubmit();">產生報表</span>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
    
        </div>
    
   
</div>

<button id="error_startDate" class="btn btn-danger mt-sweetalert" data-title="請選擇起始日期" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_endDate" class="btn btn-danger mt-sweetalert" data-title="請選擇結束日期" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_date" class="btn btn-danger mt-sweetalert" data-title="結束日期 需在 起始日期之後" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>

@endsection

@section('scripts')

<script src="{{asset('assets/global/plugins/jquery-ui/jquery-ui.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/apps/scripts/jquery.magnific-popup.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/pages/scripts/ui-sweetalert.min.js')}}" type="text/javascript"></script>

<script>

function selectSupplier() {
    $.magnificPopup.open({
        showCloseBtn : false, 
        enableEscapeKey : false,
        closeOnBgClick: true, 
        fixedContentPos: false,
        modal:false,
        type:'iframe',
        items:{src:"{{route('selectSupplier')}}"}
    });
}

function setSupplier(code,name,id){
    $.magnificPopup.close();
    var str = code+' '+name;
    $('#select_supplier').text(str);
    $('#supplier').val(id);
}

function pdfsubmit()
{
    var start = $('#startDate').val(); 
    var end = $('#endDate').val(); 
    if(start == ''){
        $('#error_startDate').click();
        return;
    } else if(end == ''){
        $('#error_endDate').click();
        return;
    }            

    if(start > end){
        $('#error_date').click();
        return;
    }

    var lot_number = $('#lot_number').val(); 
    var supplier = $('#supplier').val();
    
    openInNewTab("/pdf/yearly.php?start="+start+"&end="+end+"&lot_number="+lot_number+"&supplier="+supplier);
            
        
    
}

function openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}


</script>
@endsection