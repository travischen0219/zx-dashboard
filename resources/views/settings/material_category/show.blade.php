@extends('layouts.app')

@section('title','物料分類')

@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
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
    #save_recoder{
        color:#fff;
        background-color: #248ff1;
    }
    table thead{
        color:#fff;
        background-color: #248ff1;
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
    <h1 class="page-title"> 物料分類
        <small>建立與編輯 (可直接拖曳序號做排序)</small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')


<div class="row">
    <div class="col-md-10">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')            
                <div class="caption font-dark col-md-12">
                    <a href="{{ route('material_category.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 新增分類</a>
                    <a href="javascript:;" id="save_recoder" class="btn"><i class="fa fa-check"></i> 儲存排序變更</a>
                </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                <form id="this_form">
                    {{ csrf_field() }}
                    <input type="hidden" name="count_cates" value="{{count($material_categories)}}">
                    
                    <table class="table table-striped table-checkable table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                                <th>序 號 (可拖曳排序)</th>
                                <th>代 號</th>                                
                                <th>名 稱</th>
                                <th>操 作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($material_categories as $key=>$cate)
                                <tr class="recoder_tr_{{$key}}">
                                    <td cateid_{{$key}}="orderby_tb_{{$cate->id}}" id="td_id_{{$key}}">{{$cate->orderby}}</td>
                                    <td>{{$cate->code}}</td>
                                    <td>{{$cate->name}}</td>

                                    <td align="center">
                                        <a href="{{ route('material_category.edit', $cate->id) }}" class="btn blue btn-outline btn-sm">修改</a>

                                        <a href="javascript:;" class="btn red btn-outline btn-sm" 
                                            onclick="if(confirm('確定要刪除嗎 ?')){
                                                        event.preventDefault();
                                                        document.getElementById('delete-form-{{$cate->id}}').submit();
                                                    } else {
                                                        event.preventDefault();
                                                    }"
                                            >刪除</a>
                                        </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>

                @foreach($material_categories as $cate)
                    <form id="delete-form-{{$cate->id}}" action="{{ route('material_category.destroy', $cate->id) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                    </form>
                @endforeach

            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
        
        <button id="success_alert" class="btn btn-primary mt-sweetalert" data-title="存檔成功" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-primary" style="display: none"></button>
        <button id="error_1_alert" class="btn btn-primary mt-sweetalert" data-title="尚無資料需要排序" data-message="" data-allow-outside-click="true" data-confirm-button-class="btn-primary" style="display: none"></button>
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
    <script src="{{asset('assets/pages/scripts/table-datatables-rowreorder.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{asset('assets/pages/scripts/ui-sweetalert.min.js')}}" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    $(document).on('click', '#save_recoder',function(e){
        var cates_length = $("input[type=hidden][name=count_cates]").val();
        var data_id = [];
        var data_orderby = [];
        for (i = 0; i < cates_length; i++) {
            var id = $(".recoder_tr_"+i+" td").attr('cateid_'+i).substr(11);
            var orderby = $("#td_id_"+i).html();
            data_id.push(id); 
            data_orderby.push(orderby); 
        }

        $.post(
            "{{ route('material_category.update.orderby') }}",
            {'_token':"{{csrf_token()}}",'data_id':data_id,'data_orderby':data_orderby},
            function(response){
                if(response == 'success'){
                    $('#success_alert').click();
                } else if(response == 'error_1'){
                    $('#error_1_alert').click();
                }
            }
        );

        e.preventDefault();
    });


</script>
@endsection