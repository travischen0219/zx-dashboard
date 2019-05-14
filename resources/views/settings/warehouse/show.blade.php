@extends('layouts.app')

@section('title','倉儲資料')

@section('css')
<link href="{{asset('assets/apps/css/style.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />

<style>

a{text-decoration: none !important;}

a:hover {text-decoration: none !important;}

.info-box .action {
  text-align: right;
  
}

.info-box .content .text {
  margin: 0;
  font-size: 16px;
  position: absolute;
  top: 8px;
  right: 12px;
}

.info-box .content .text a + a {
  margin-left: 2px;
}

.info-box .content .number {
  margin-top: 5px;
  overflow: hidden;
  width: 260px;
}
.info-box .content .title {
  font-size: 18px;
}

.info-box .text {
  display: none;
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
    <h1 class="page-title"> 倉儲資料
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')

<div class="row">
    <form role="form" action="{{ route('warehouses.search') }}" method="POST">
        {{ csrf_field() }}
        <div class="col-md-12" >
            <div class="form-body" style="border-bottom: 1px solid #eeeeee;padding-bottom: 50px;padding-top: 25px;">
                <div class="form-group">
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center"> 分類 :</label>
                        <div class="col-md-8">
                            <select class="form-control" style="font-size: 14px;" name="search_category" id="search_category">
                                @foreach($cates as $cate)
                                    <option value="{{$cate->id}}" {{ $search_code == $cate->id ? 'selected' : '' }}> {{$cate->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="col-md-3 control-label" style="color:#248ff1;font-size: 16px;line-height: 32px;text-align: center">編號/名稱 :</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="search_codeOrName" id="search_codeOrName">
                        </div>
                    </div>
                  
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-lg" style="background-color: #248ff1;color:#fff;font-size: 16px;">搜 尋</button>
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
                <div class="caption font-dark col-md-12">
                    <a href="{{ route('warehouses.create') }}" class="btn btn-primary" ><i class="fa fa-plus"></i> 新增倉儲</a>

                </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">

                @if(count($warehouses) > 0)
                    @foreach($warehouses as $warehouse)

                        <div class="col-md-6">
                            <div class="info-box hover-zoom-effect">
                                <div class="icon bg-cyan">
                                    <a href='javascript: barcode("{{$warehouse->fullName}}", "{{$warehouse->code}}");'>
                                        <i class="glyphicon glyphicon-th-large" aria-hidden="true"></i>
                                    </a>
                                </div>
                                <div class="content">
                                    <div class="text">
                                        <a href="{{ route('warehouses.edit', $warehouse->id) }}" class="col-amber"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                                        <a href="javascript:;" class="col-amber" onclick="
                                            if(confirm('確定要刪除嗎 ?')){
                                                event.preventDefault();
                                                document.getElementById('delete-form-{{$warehouse->id}}').submit();
                                            } else {
                                                event.preventDefault();
                                            }"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i>
                                        </a>
                                        <form id="delete-form-{{$warehouse->id}}" action="{{ route('warehouses.destroy', $warehouse->id) }}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                    </div>
                                    <div class="number col-blue-grey">
                                        <a href="{{ route('warehouses.show', $warehouse->id) }}">
                                            <div class="title col-orange">
                                                {{$warehouse->warehouse_category->name}}                            <span class="col-blue-grey">{{$warehouse->fullName}}</span>
                                            </div>
                                            <div>{{$warehouse->code}}
                                                @if($warehouse->status == 2)
                                                    <span style="color:red;"> (關閉)</span>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    @endforeach

                @else
                    <div class="col-md-6">
                        <span>查無資料</span>
                    </div>
                @endif
                
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
        
    </div>
</div>

@endsection

@section('scripts')

<script src="{{asset('assets/apps/scripts/jquery.magnific-popup.js')}}" type="text/javascript"></script>
<script>

$(function() {
    $(".info-box").hover(function() {
        $(this).find('.text').show();
    }, function() {
        $(this).find('.text').hide();
    });
    
});  

function barcode(title, code) {
    
    // $.get("{{route('barcode')}}",{code: code, title: title},function(response){
    //     var chk = $('#barc_'+id).attr('chk');
    //     if(chk == 0){
    //         $('#barc_'+id).append(response);
    //         $('#barc_'+id).attr('chk',1);
    //     } else {
    //         $('#barc_'+id).html('');
    //         $('#barc_'+id).attr('chk',0);
    //     }
    // });

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



</script>
@endsection