@extends('b4.app')

@section('title','首頁')
@section('page-header','首頁 - 版本更新記錄')

@section('content')

<div class="row">
    <ul style="font-size: 24px;">
        <li>
            2019/10/7
            <li>
                [庫存盤點 - 出入庫] 修正差異處理後顯示異常問題
                <br>
                <img src="/images/dashboard/fix.png" width="300" style="margin: 10px 0;" />
            </li>
        </li>
        <li>
            2019/10/6
            <ul>
                <li>[銷貨出貨 - 銷貨] 修正報價單列印標題</li>
                <li>
                    [銷貨出貨 - 銷貨] 新增稅率欄位
                    <br>
                    <img src="/images/dashboard/tax1.png" width="500" style="margin: 10px 0;" />
                    <img src="/images/dashboard/tax2.png" width="500" style="margin: 10px 0;" />
                    <img src="/images/dashboard/tax3.png" width="500" style="margin: 10px 0;" />
                </li>
            </ul>
        </li>
        <li>
            2019/10/02
            <ul>
                <li>
                    [銷貨出貨 - 銷貨] 修改了報價單樣式並且可以顯示模組照片 (1~3張)
                    <br>
                    <img src="/images/dashboard/bjd.png" width="600" style="margin: 10px 0;" />
                </li>
                <li>
                    [庫存盤點 - 出入庫]
                    因為主機某個設定遺漏造成出入庫類別無法選取問題已經修復
                    <br>
                    <img src="/images/dashboard/crk.png" width="600" style="margin: 10px 0;" />
                </li>
                <li>
                    [基本資料 - 物料管理]
                    庫存不正確問題同上，修正後庫存數已經正確
                    <br>
                    <img src="/images/dashboard/kc.png" width="600" style="margin: 10px 0;" />
                </li>
                <li>
                    [庫存盤點 - 出入庫]
                    庫存出入庫清單增加顯示備註
                    <br>
                    <img src="/images/dashboard/kmemo.png" width="600" style="margin: 10px 0;" />
                </li>
            </ul>
        </li>

        <hr>

        <li>
            2019/09/27
            <ul>
                <li>[基本資料 - 客戶] 新增的客戶預設啟用，現在會出現在客戶選擇清單了</li>
                <li>[銷貨出貨 - 銷貨] 報價中的銷貨單不需要批號，批號改為選填</li>
            </ul>
        </li>
        <li>2019/09/08</li>
        <ul>
            <li>[採購進貨 - 在途量] 在途量現在會包含轉加工的數量</li>
            <li>[採購進貨 - 採購] 採購去掉了加工完成狀態</li>
            <li>
                [採購進貨 - 採購] 鋁條成本計算方式<br>
                <span class="text-primary">
                    成本自斷換算需要增加不少欄位來讓電腦做計算，反而需要更多操作<br>
                    鋁條單位成本，以及計價成本可以自行計算好後，填入物料基本資料中，或是在採購時後輸入<br>
                    就會得到正確數字，理論上計價總成本 = 總成本 (誤差1元內)
                </span>
                <br>
                <img src="/images/dashboard/cal.png" width="600" style="margin: 10px 0;" />
                <br>
                <img src="/images/dashboard/cal2.png" width="600" style="margin: 10px 0;" />
            </li>
        </ul>

        <li>2019/07/24
            <ul>
                <li>[基本資料] [採購進貨] 改版整合優化</li>
            </ul>
        </li>
        <li>2019/07/13
            <ul>
                <li>[基本資料] 整合 物料清單、圖片清單</li>
            </ul>
        </li>
        <li>2019/07/07
            <ul>
                <li>[基本資料] 新增批號管理</li>
                <li>[基本資料] 整合 員工資料、部門設定、職稱設定 至 [公司資料]</li>
            </ul>
        </li>
        <li>2019/05/31
            <ul>
                <li>
                    [基本資料 - 物料模組] 列印欄位新增數量
                </li>
                <li>
                    [基本資料 - 物料模組] 批量些改採用原數量x倍數處理
                </li>
            </ul>
        </li>
        <li>2019/05/21</li>
        <ul>
            <li>
                [基本資料 - 物料模組] 新增模組內容列印<br>
                <img src="/images/dashboard/module.print.png" style="max-width: 400px; margin: 10px 0;" />
            </li>
            <li>
                [採購進貨 - 採購] 調整列印單張跟多張採購單版面<br>
                <img src="/images/dashboard/buy.print.png" style="max-width: 400px; margin: 10px 0;" />
            </li>
        </ul>

        <li>2019/05/20</li>
        <ul>
            <li>[基本資料 - 物料管理] 修復庫存紀錄無法顯示的問題</li>
            <li>[庫存盤點 - 盤點] 修復快速修正的算式</li>
            <li>
                [採購進貨 - 採購] 新增採購時可顯示目前庫存<br>
                <img src="/images/dashboard/buy.stock.png" style="max-width: 400px; margin: 10px 0;" />
            </li>
        </ul>

        <li>2019/05/19</li>
        <ul>
            <li>
                [庫存盤點 - 盤點] 新增快速修正盤點差異數量：快速修正後，會自動加入誤差紀錄，以及修正物料跟倉庫內的數量<br>
                <img src="/images/dashboard/quick_fix.png" style="max-width: 800px; margin: 10px 0;" />
            </li>
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

@endsection

@section('script')

@endsection
