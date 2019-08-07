@extends('layouts.app')

@section('title','盤點表')

@section('css')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}" rel="stylesheet" type="text/css" />
<!-- END PAGE LEVEL PLUGINS -->
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />

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
    <h1 class="page-title"> 盤點表 <a href="{{ route('inventory.index') }}" class="btn blue" style="margin-top:-3px;"><i class="fa fa-reply"></i> 返 回</a>
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->

</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')




<div class="row">
    <div class="col-md-12" style="margin-top:25px;">

    </div>

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')
                <div class="caption font-dark">

                </div>
                <div class="tools"> </div>
            </div>



            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover" id="sample_1" >
                    <thead>
                        <tr>
                            <th>倉 儲</th>
                            <th>物料編號</th>
                            <th>分 類</th>
                            <th>品 名</th>
                            <th>單 位</th>
                            <th>尺 寸</th>
                            <th>應有庫存</th>
                            <th>盤點數量</th>
                            <th>異 常</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($materials as $material)
                            @if(true)
                            <tr>
                                <td>{{$material->warehouse_name->code}}</td>
                                <td>{{$material->material_name->fullCode}}</td>
                                <td>
                                    @if($material->material_name->material_categories_code == '')
                                        <span style="color:red;">未指派</span>
                                    @else
                                        [ {{$material->material_name->material_categories_code}} ] {{$material->material_name->material_category_name->name}}
                                    @endif
                                </td>

                                <td>{{$material->material_name->fullName}}</td>
                                <td>
                                    @if($material->material_name->unit > 0 )
                                        {{$material->material_name->material_unit_name->name}}
                                    @else
                                        <span style="color:red;">未指派</span>
                                    @endif
                                </td>
                                <td>{{$material->material_name->size}}</td>
                                <td align="right">
                                    {{$material->original_inventory}}
                                </td>
                                <td align="right">
                                    <span>{{ $material->physical_inventory > 0 ? $material->physical_inventory : ''}}</span>
                                </td>
                                <td align="center">
                                    @if($material->physical_inventory == '')
                                        <span style="color:red;">未盤點</span>
                                    @elseif(($material->physical_inventory - $material->original_inventory) != 0)
                                        <span style="color:red;">
                                            {{$material->physical_inventory - $material->original_inventory}}

                                            @if ($material->quick_fix == 0)
                                                <button type="button"
                                                    class="btn btn-sm btn-warning"
                                                    onclick="quickFix({{ $material->id }});"
                                                    style="float: right;">
                                                    <small style="float: right;">快速修正</small>
                                                </button>
                                            @else
                                                <small class="text-success">已修正</small>
                                            @endif
                                        </span>
                                    @elseif(($material->physical_inventory - $material->original_inventory) == 0)
                                        <span style="color:green;">正確</span>
                                    @endif
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
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>

<script>
$( document ).ready(function() {
    $('input[type="search"]').focus();

});

function quickFix(id) {
    swal({
        title: "快速修正",
        text: "將會依照差異數量自動填入誤差處理中，是否繼續？",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: '確定',
        cancelButtonText: '取消',
        closeOnConfirm: false
    }, function () {
        location.href = '/stock/inventory/quick_fix/{{ $inventory->id }}/' + id;
    });
}

</script>
@endsection
