@extends('layouts.app')

@section('title','半成品進度追蹤')

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
    <h1 class="page-title"> 半成品進度追蹤
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
     <form role="form" action="{{ route('semi_finished_schedule.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#8781d2;font-size: 16px;line-height: 32px;text-align: center"> 狀態 :</label>
                        <div class="col-md-9">
                            <select class="form-control" style="font-size: 14px;" name="search_category">
                                <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                <option value="1" {{$search_code == 1 ? 'selected' : ''}}>加工中</option>
                                <option value="2" {{$search_code == 2 ? 'selected' : ''}}>加工完成</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#8781d2;font-size: 16px;line-height: 32px;text-align: center">名稱 :</label>
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
                <div class="caption font-dark">
                    <a href="{{ route('semi_finished_schedule.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增半成品進度追蹤</a>
                </div>
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_3" >
                    <thead>
                        <tr>
                            <th>開始日期</th>
                            <th>完成日期</th>
                            <th>名稱</th>
                            <th>狀態</th>
                            <th>備註</th>
                            <th>工作進度紀錄</th>
                            <th>操 作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                         @foreach($processings as $processing)

                            <tr>
                                <td>{{$processing->start_date}}</td>
                                <td>{{$processing->end_date}}</td>
                                <td>{{$processing->lot_number}}</td>                            
                                <td>
                                    @if($processing->status == '1') 
                                    <span style="color:red">加工中</span> 
                                    @elseif($processing->status == '2') 
                                    <span style="color:blue">加工完成</span>
                                    @endif
                                </td>
                                <td>{{$processing->memo}}</td>
                                <td>
                                    @if($processing->status == '1') 
                                        {{-- <a href="{{ url('stock/semi_finished_schedule/edit_list/'.$processing->id) }}" class="btn green btn-outline btn-sm">進度紀錄</a>  --}}
                                        <a href="{{ route('processing_list.edit',$processing->id) }}" class="btn green btn-outline btn-sm">進度紀錄</a> 
                                    @elseif($processing->status == '2') 
                                        {{-- <a href="{{ url('stock/semi_finished_schedule/show_list/'.$processing->id) }}" class="btn purple btn-outline btn-sm">查看</a> --}}
                                        <a href="{{ route('processing_list.show',$processing->id) }}" class="btn purple btn-outline btn-sm">查看</a>
                                    @endif
                                </td>
                                <td align="center" id="functions_btn">
                                    @if($processing->status == 1)
                                        <a href="{{ route('semi_finished_schedule.edit', $processing->id) }}" class="btn blue btn-outline btn-sm">修改</a>
                                        <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                                            if(confirm('確定要刪除嗎 ?')){
                                                event.preventDefault();
                                                document.getElementById('delete-form-{{$processing->id}}').submit();
                                            } else {
                                                event.preventDefault();
                                            }">刪除</a>
                                        <form id="delete-form-{{$processing->id}}" action="{{ route('semi_finished_schedule.destroy', $processing->id) }}" method="post" style="display:none">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                    @elseif($processing->status == 2)
                                        <a href="{{ route('semi_finished_schedule.show', $processing->id) }}" class="btn purple btn-outline btn-sm">查看</a>
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