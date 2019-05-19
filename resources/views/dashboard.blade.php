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
    </h1>
    <!-- END PAGE TITLE-->

</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')
<!-- BEGIN DASHBOARD STATS 1-->
<div class="row">
    <ul style="font-size: 24px;">
        <li>2019/05/19</li>
        <ul>
            <li>
                [基本資料 - 物料模組] 新增批量修改數量<br>
                <img src="/images/dashboard/module.batch.png" width="800" style="margin: 10px 0;" />
            </li>
        </ul>

        <li>2019/05/18</li>
        <ul>
            <li>[採購進貨 - 報表] 合併月報表及年報表</li>
            <li>[採購進貨 - 報表] 可選輸出欄位</li>
            <li>
                [採購進貨 - 報表] 修正資料來源<br>
                <img src="/images/dashboard/print.buy.png" width="800" style="margin: 10px 0;" />
            </li>
        </ul>

        <li>2019/05/17</li>
        <ul>
            <li>修復 [採購進貨 - 採購換貨] 無法執行的問題</li>
        </ul>

        <li>2019/05/16</li>
        <ul>
            <li>[採購進貨 - 應收帳款] 增加供應商搜尋選項</li>
            <li>[採購進貨 - 應收帳款] 修復金額顯示</li>
            <li>[採購進貨 - 應收帳款] 增加列印未付款資料</li>
        </ul>

        <li>2019/05/15</li>
        <ul>
            <li>修正採購單列印</li>
        </ul>
    </ul>
</div>
<div class="clearfix"></div>
<!-- END DASHBOARD STATS 1-->

@endsection
