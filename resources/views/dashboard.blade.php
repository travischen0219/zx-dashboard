@extends('layouts.app')

@section('title','首頁')

@section('page_header')
<!-- BEGIN PAGE HEADER-->

<!-- BEGIN PAGE BAR -->
<div class="page-bar">

    <!-- BEGIN THEME PANEL -->
    @include('layouts.theme_panel')
    <!-- END THEME PANEL -->


    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 真心蓮坊進銷存系統
        <small>測試文字</small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')
<!-- BEGIN DASHBOARD STATS 1-->
<div class="row">
   
</div>
<div class="clearfix"></div>
<!-- END DASHBOARD STATS 1-->

@endsection