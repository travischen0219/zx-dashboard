@extends('layouts.app')

@section('title','採購換貨')

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
    <h1 class="page-title"> 採購換貨
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
    <form role="form" action="{{ route('p_exchange.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 狀態 :</label>
                        <div class="col-md-9">
                            <select class="form-control" style="font-size: 14px;" name="search_category">
                                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>換貨中</option>
                                <option value="2" {{$search_code == 2 ? 'selected' : ''}}>換貨完成</option>
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
    <form role="form" action="{{ route('p_exchange.create') }}" method="GET" id="create_from">
        <div class="col-md-12" style="margin-top: 20px;">
            <div class="col-md-5">
                <label class="col-md-3 control-label" style="color:red;font-size: 16px;line-height: 32px;text-align: center">採購單號 :</label>
                <div class="col-md-9">
                    <input type="text" class="form-control" name="buy_no" id="buy_no" value="{{ old('buy_no') }}">
                </div>
            </div> 
            <div class="col-md-2">
                <button type="button" onclick="submit_btn();" class="btn btn-primary" style=""><i class="fa fa-plus"></i> 新增換貨</button>
            </div>
        </div>
    </form>
    
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark">
                    {{-- <a href="{{ route('buy.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增採購</a> --}}
                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="caption font-dark" style="margin-left:5px">
                    <span class="btn btn-primary" onclick="pdfsubmit();"><i class="fa fa-print"></i> 多筆PDF列印</span>
                </div>
                
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_3" >
                    <thead>
                        <tr>
                            <th>採購單號</th>
                            <th>PDF列印</th>
                            <th>批號</th>
                            <th>供應商</th>
                            <th>說明</th>
                            <th>換貨日期</th>
                            <th>換貨完成日</th>
                            <th>採購狀態</th>
                            <th>換貨狀態</th>
                            <th>操 作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($exchanges as $exchange)

                            <tr>
                                
                                <td>P{{$exchange->buy_no}}</td>
                                <td><a href="/pdf/?id={{$exchange->id}}" target="_blank" class="btn blue btn-outline btn-sm">列印</a>&nbsp;&nbsp;
<input type="checkbox" class="print_pdf" name="print_pdf"  value="{{$exchange->id}}">
                                </td>
                                <td>{{$exchange->lot_number}}</td>                              
                                <td>{{$exchange->supplier_name->shortName}}</td>
                                <td>{{$exchange->memo}}</td>
                                <td>{{$exchange->exchangeDate}}</td>
                                <td>{{$exchange->realExchangeDate}}</td>
                                <td>
                                    @if($exchange->buy_name->status == '1') 
                                        <span style="color:red">未採購</span> 
                                    @elseif($exchange->buy_name->status == '2') 
                                        <span style="color:blue">已採購</span>
                                    @elseif($exchange->buy_name->status == '3') 
                                        <span style="color:purple">已到貨</span> 
                                    @elseif($exchange->buy_name->status == '11') 
                                        <span style="color:#248ff1">轉半成品</span>
                                    @elseif($exchange->buy_name->status == '4') 
                                        <span style="color:green">已轉到入庫</span>    
                                    @endif
                                </td>
                                <td>
                                    @if($exchange->status == '1') 
                                        <span style="color:red">換貨中</span> 
                                    @elseif($exchange->status == '2') 
                                        <span style="color:blue">換貨完成</span>                                         
                                    @endif
                                </td>
                                <td align="center" id="functions_btn">

                                    
                                    @if($exchange->status == 1)
                                        <a href="{{ route('p_exchange.edit', $exchange->id) }}" class="btn blue btn-outline btn-sm">修改</a>
                                    @elseif($exchange->status == 2)
                                        <a href="{{ route('p_exchange.edit', $exchange->id) }}" class="btn purple btn-outline btn-sm">查看</a>
                                    @endif
                                    {{-- <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                                        if(confirm('確定要刪除嗎 ?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$buy->id}}').submit();
                                        } else {
                                            event.preventDefault();
                                        }">刪除</a>
                                    <form id="delete-form-{{$buy->id}}" action="{{ route('buy.destroy', $buy->id) }}" method="post" style="display:none">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form> --}}
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
<button id="error_buy_no_prefix" class="btn btn-danger mt-sweetalert" data-title="採購單號開頭為 P" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>
<button id="error_buy_no" class="btn btn-danger mt-sweetalert" data-title="採購單號長度為12個字" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-danger" style="display: none;"></button>

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



function submit_btn(){
    if($('#buy_no').val().substr(0,1) != 'P'){
        if($('#buy_no').val().substr(0,1) != 'p'){
            $('#error_buy_no_prefix').click();
            return;
        }
    }
    if($('#buy_no').val().trim().length != 12){
        $('#error_buy_no').click();
        return;
    }
    $("#create_from").submit();
}
</script>
@endsection