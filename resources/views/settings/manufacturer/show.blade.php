@extends('layouts.app')

@section('title','廠商資料')

@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />

<style>
    a{
        text-decoration:none;
    }
    #sample_1 td{
        font-size: 16px;
        vertical-align:middle;
    }
    #sample_1 th{
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
    #sample_1_filter input { 
        width:300px !important;
    }
    #popup{
    width:400px;
    height:160px;
    display:block; 
    background-color: white;
    margin:auto;
    }
    #popup p{
        padding-top:20px;
        display:block; 
        text-align:center;
    }
    #popup img{
        display:block; 
        margin:0 auto ;
        padding:0px 20px;
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
    <h1 class="page-title"> 廠商資料
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')




<div class="row">
    <div class="col-md-12" >
        <form role="form" action="{{ route('manufacturer.search') }}" method="POST" id="search_from">
            {{ csrf_field() }}
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 56px;padding-top: 20px;">
                <div class="col-md-5">
                    <div class="form-group">
                            <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 篩選分類 :</label>
                            <div class="col-md-9">
                                <select class="form-control" style="font-size: 14px;" name="search_category" onchange="search();">
                                    <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                    <option value="1" {{$search_code == 1 ? 'selected' : ''}}>常用</option>
                                    <option value="2" {{$search_code == 2 ? 'selected' : ''}}>不常用</option>
                                    <option value="" {{$search_code == '' ? 'selected' : ''}}>未設定</option>
                                </select>
                            </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group">
                        {{-- <button type="submit" class="btn" style="background-color: #248ff1;color:#fff;font-size: 14px;">篩選</button> --}}
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark">
                    <a href="{{ route('manufacturer.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增廠商</a>
                </div>
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    <thead>
                        <tr>
                            <th width="10%">編 號</th>
                            <th width="10%">全 名</th>
                            <th width="10%">聯絡人</th>
                            <th width="10%">電 話</th>
                            <th width="30%">備 註</th>
                            <th width="30%">操 作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($manufacturers as $manufacturer)
                            @if(true)
                            <tr>
                                <td>{{$manufacturer->code}}</td>
                                <td><a href="{{ route('manufacturer.show', $manufacturer->id) }}">{{$manufacturer->fullName}}</a></td>
                                <td>{{$manufacturer->contact}}</td>
                                <td>{{$manufacturer->tel}}</td>
                                <td>{{$manufacturer->memo}}</td>
                                <td align="center" id="functions_btn">
                                    {{-- <a href="{{ route('manufacturer.show', $manufacturer->id) }}" class="btn purple btn-outline btn-sm">查看</a> --}}
                                    <a href="{{ route('manufacturer.edit', $manufacturer->id) }}" class="btn blue btn-outline btn-sm">修改</a>
                                    <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                                        if(confirm('確定要刪除嗎 ?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$manufacturer->id}}').submit();
                                        } else {
                                            event.preventDefault();
                                        }">刪除</a>
                                    <a href='
                                        @if($manufacturer->code != '' && $manufacturer->fullName != '')
                                            javascript: barcode("{{$manufacturer->fullName}}", "{{$manufacturer->code}}");' class="btn green btn-outline btn-sm">條碼</a>
                                        @else
                                            javascript:;' class="btn green btn-outline btn-sm" disabled>條碼</a>
                                        @endif
                                    <form id="delete-form-{{$manufacturer->id}}" action="{{ route('manufacturer.destroy', $manufacturer->id) }}" method="post" style="display:none">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form>
                                </td>
                            </tr>
                            @endif
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
<script src="{{asset('assets/apps/scripts/jquery.magnific-popup.js')}}" type="text/javascript"></script>

<script>
function barcode(title, code) {
    $.magnificPopup.open({
        showCloseBtn : false, 
        enableEscapeKey : false,
        closeOnBgClick: true, 
        fixedContentPos: false,
        modal:false,
        type:'ajax',
        items:{src:"{{route('barcode')}}"},
        ajax: {
            settings: {
                type: 'GET',
                data: { 
                    title: title, code: code
                }
            }
        }
    });
}


// $( "#search" ).change(function() {
//     search();
// });

function search(){
    $("#search_from").submit();
}

</script>
@endsection