@extends('layouts.app')

@section('title','物料管理')

@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />

<style>
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
    #pop_stock{
        width:85%;
        height:900px;
        display:block; 
        background-color: white;
        margin:auto;
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
    <h1 class="page-title"> 物料管理
        <small>資料建立與編輯</small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')




<div class="row">
    <div class="col-md-12" >
        <form role="form" action="{{ route('materials.search') }}" method="POST" id="search_from">
            {{ csrf_field() }}
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 56px;padding-top: 20px;">
                <div class="col-md-5">
                    <div class="form-group">
                            <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 篩選分類 :</label>
                            <div class="col-md-9">
                                <select class="form-control" style="font-size: 14px;" name="search_category" onchange="search();">

                                    <option value="all" {{$search_code == 'all' ? 'selected' : ''}}>全部</option>
                                    @foreach($material_categories as $cate)
                                        <option value="{{$cate->code}}" {{ $search_code == $cate->code ? 'selected' : '' }}>[ {{$cate->code}} ] {{$cate->name}}</option>
                                    @endforeach
                                  
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
                    <a href="{{ route('materials.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增物料</a>
                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="caption font-dark" style="margin-left:5px">
                    <span class="btn btn-primary" onclick="pdfsubmit();"><i class="fa fa-print"></i> 多筆PDF列印</span>
                </div>
                <div class="tools"> </div>
            </div>

            
                
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    <thead>
                        <tr>
                            <th>編 號</th>
                            <th>列 印</th>
                            <th>分 類</th>
                            <th>品 名</th>
                            <th>單 位</th>
                            <th>尺 寸</th>
                            <th>安全量</th>
                            <th>庫 存</th>
                            <th>操 作</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($materials as $material)
                            @if(true)
                            <tr>
                                
                                <td>{{$material->fullCode}}</td>
                                <td>
                                    <a href="{{url('barcode_PDF/'.$material->id)}}" target="_blank" class="btn blue btn-outline btn-sm">列印</a>&nbsp;&nbsp;
                                    <input type="checkbox" class="print_pdf" name="print_pdf"  value="{{$material->id}}">
                                </td>
                                <td>
                                    @if($material->material_categories_code == '')
                                        <span style="color:red;">未指派</span>
                                    @else
                                        [ {{$material->material_categories_code}} ] {{$material->material_category_name->name}}
                                    @endif
                                </td>
                                
                                <td><a href="{{ route('materials.show', $material->id) }}">{{$material->fullName}}</a></td>
                                <td>
                                    @if($material->unit > 0 )
                                        {{$material->material_unit_name->name}}
                                    @else
                                        <span style="color:red;">未指派</span>
                                    @endif
                                </td>
                                <td>{{$material->size}}</td>

                                @if($material->safe >0)
                                    <td>{{$material->safe}}</td>
                                @else
                                    <td><span style="color:red;">未設定</span></td> 
                                @endif

                                @if($material->safe >= $material->stock )
                                    <td style="">
                                    <font color="red">{{$material->stock}}</font>
                                @else
                                    <td >
                                    {{$material->stock}}
                                @endif

                                    <a href="javascript: show_stock('{{ $material->id }}');" class="btn blue btn-outline btn-sm pull-right">庫存紀錄</a>
                                </td>
                                <td align="center" id="functions_btn">
                                    {{-- <a href="{{ route('materials.show', $material->id) }}" class="btn purple btn-outline btn-sm">查看</a> --}}
                                    <a href="{{ route('materials.edit', $material->id) }}" class="btn blue btn-outline btn-sm">修改</a>
                                    <a href="javascript:;" class="btn red btn-outline btn-sm" onclick="
                                        if(confirm('確定要刪除嗎 ?')){
                                            event.preventDefault();
                                            document.getElementById('delete-form-{{$material->id}}').submit();
                                        } else {
                                            event.preventDefault();
                                        }">刪除</a>
                                    <a href='
                                        @if($material->fullCode != '' && $material->fullName != '')
                                            javascript: barcode("{{$material->fullName}}", "{{$material->fullCode}}");' class="btn green btn-outline btn-sm">條碼</a>
                                        @else
                                            javascript:;' class="btn green btn-outline btn-sm" disabled>條碼</a>
                                        @endif
                                    <form id="delete-form-{{$material->id}}" action="{{ route('materials.destroy', $material->id) }}" method="post" style="display:none">
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

function show_stock(id) {
    $.magnificPopup.open({
        showCloseBtn : true, 
        enableEscapeKey : false,
        closeOnBgClick: false, 
        fixedContentPos: true,
        modal:true,
        type:'ajax',
        items:{src:"{{route('show_stock')}}"},
        ajax: {
            settings: {
                type: 'GET',
                data: { 
                    id: id
                }
            }
        }
    });
}

function close_show_stock(){
    $.magnificPopup.close();
}

function search(){
    $("#search_from").submit();
}

function pdfsubmit()
{
           
            var chkArray = [];
          
            $(".print_pdf:checked").each(function() {
                chkArray.push($(this).val());
            });
            
            /* we join the array separated by the comma */
            var selected;
            selected = chkArray.join(',');
            var url = "{!!url('barcode_PDF')!!}"+"/"+selected;
            openInNewTab(url);
        
    
}
function openInNewTab(url) {
  var win = window.open(url, '_blank');
  win.focus();
}
</script>
@endsection