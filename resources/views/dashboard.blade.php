@extends('b4.app')

@section('title','首頁')
@section('page-header','首頁 - 版本更新記錄')

@section('content')

<div class="row">
    <ul style="font-size: 24px;">
        <li>2021/11/07</li>
        <ul>
            <li>
                選擇批號 - 選擇批號視窗現在可選已完工的批號
                <br>
                <img src="/images/dashboard/0102.png" width="500" style="margin: 10px 0;" />
            </li>
            <li>
                銷貨報表 - 銷貨報表成本現在會加上管銷成本 (金額為測試數字)
                <br>
                <img src="/images/dashboard/addcost1.png" width="500" style="margin: 10px 0;" />
                <br>
                <img src="/images/dashboard/addcost2.png" width="500" style="margin: 10px 0;" />
            </li>
        </ul>
        <li>2021/08/14</li>
        <ul>
            <li>
                基本資料 - 修改物料模組 可更新價錢
                <br>
                <img src="/images/dashboard/upp.png" width="500" style="margin: 10px 0;" />
            </li>
        </ul>
        <li>2021/07/06</li>
        <ul>
            <li>
                基本資料 - 物料模組可以選擇單位 (需要的單位也是在基本資料新增)
                <br>
                <img src="/images/dashboard/mmunit.png" width="300" style="margin: 10px 0;" />
            </li>
            <li>有設定單位的模組會在表單或報表出現</li>
        </ul>
        <li>2021/05/16</li>
        <ul>
            <li>採購各項物料可單獨入庫</li>
            <li>已完成採購單不再自動入庫以保持庫存數量正確</li>
            <li>單獨入庫輸入後畫面會重新整理，請不要修改採購單入庫數量以外欄位</li>
            <li>
                在途量會扣除已經單獨入庫的數量
                <br>
                <img src="/images/dashboard/ain1.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                採購單加大字型
                <br>
                <img src="/images/dashboard/ain2.png" width="600" style="margin: 10px 0;" />
            </li>
        </ul>

        <li>2021/04/22</li>
        <ul>
            <li>
                銷貨單的物料模組可增加註解
                <br>
                <img src="/images/dashboard/ssm.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                銷貨單、報價單版面調整
            </li>
            <li>
                採購單日期問題修正
            </li>
        </ul>
        <li>2021/03/31</li>
        <ul>
            <li>
                公司資料由於可以更改別人或自己的權限故從基本資料獨立出來
                <br>
                <img src="/images/dashboard/access-3.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                公司資料可管理各種權限角色
                <br>
                <img src="/images/dashboard/access-1.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                員工帳號可指定任一權限角色(會影響該帳號能使用或管理的功能)
                <br>
                <img src="/images/dashboard/access-2.png" width="600" style="margin: 10px 0;" />
            </li>
        </ul>
        <li>2020/08/21</li>
        <ul>
            <li>合併供應商跟加工商</li>
        </ul>
        <li>2020/08/12</li>
        <ul>
            <li>
                列印物料模組可以輸出成Excel格式
                <br>
                <img src="/images/dashboard/excel.png" width="600" style="margin: 10px 0;" />
            </li>
        </ul>
        <li>2020/03/08</li>
        <ul>
            <li>
                修改列印物料模組<br>
                1. 庫存改為成本<br>
                2. 增加總金額<br>
                3. 增加簽核欄位
                <br>
                <img src="/images/dashboard/r1.png" width="800" style="margin: 10px 0;" />
            </li>
            <li>
                修改銷貨報表<br>
                只顯示批號 客戶 本案總成本 本案總金額
                <br>
                <img src="/images/dashboard/r2.png" width="800" style="margin: 10px 0;" />
            </li>
        </ul>
        <li>2020/03/01</li>
        <ul>
            <li>
                銷貨增加出庫之後可以取消的功能，並且會補回庫存
                <br>
                出貨後又取消的銷貨單會保留在管理介面上以查閱庫存紀錄
                <br>
                但是不會出現在成本報表中
                <br>
                <img src="/images/dashboard/out1.png" width="800" style="margin: 10px 0;" />
                <br>
                <img src="/images/dashboard/out2.png" width="600" style="margin: 10px 0;" />
            </li>
        </ul>
        <li>2020/01/18</li>
        <ul>
            <li>修復物料理面的新增圖無法點開問題</li>
            <li>
                單獨列印採購單移除單位欄位並修正漏字
                <br>
                <img src="/images/dashboard/in-detail.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                銷貨單轉出庫若庫存不足無法存檔
                <br>
                <img src="/images/dashboard/less.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                一般出庫若庫存不足無法存檔
                <br>
                <img src="/images/dashboard/less2.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                若已入庫的銷售單才發現錯誤，可填一張內容相同但數量是負的互相抵銷並留下紀錄(如圖所示)
                <br>
                處此情形之外請勿輸入負的數量
                <br>
                因為銷貨單只看模組不看物料細項所以清單上的資訊會較少
                <br>
                <img src="/images/dashboard/back.png" width="600" style="margin: 10px 0;" />
            </li>
        </ul>
        <li>2019/12/14</li>
        <ul>
            <li>
                採購報表加上簽核欄位
                <br>
                <img src="/images/dashboard/pin.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                銷貨報表加上簽核欄位
                <br>
                <img src="/images/dashboard/pout.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                出入庫功能可選年月區間
                <br>
                <img src="/images/dashboard/stock_date.png" width="600" style="margin: 10px 0;" />
            </li>
        </ul>
        <li>2019/12/11</li>
        <ul>
            <li>
                採購報表加上總金額
                <br>
                <img src="/images/dashboard/total.png" width="600" style="margin: 10px 0;" />
            </li>
        </ul>
        <li>2019/11/13</li>
        <ul>
            <li>
                重新配置物料成本計算方式
                <br>
                <img src="/images/dashboard/cost.jpg" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                物料模組增加成本顯示
                <br>
                <img src="/images/dashboard/m_cost.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                採購物料清單增加更新價格功能 (根據物料基本資料設定的價格)
                <br>
                <img src="/images/dashboard/update.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                銷貨物料模組清單增加更新價格功能 (根據物料模組基本資料設定的價格)
                <br>
                <img src="/images/dashboard/update2.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                銷貨管銷費用 (建議建立一個通用的管銷費用模組，可在銷貨單內設定價錢比較快，其餘資料可以寫在備註說明)
                <br>
                <img src="/images/dashboard/gx.png" width="600" style="margin: 10px 0;" />
            </li>
            <li>
                採購加工費用 (建議建立一個通用的加工費用物料，可在採購單內設定價錢比較快，其餘資料可以寫在備註說明，分類可以放在其他或是新建一個分類例如雜項)
                <br>
                <img src="/images/dashboard/jg.png" width="600" style="margin: 10px 0;" />
            </li>
        </ul>
        <hr class="my-5">
        <li>2019/11/03</li>
            <ul>
                <li>
                    銷貨報表增加統計部分
                    <br>
                    <img src="/images/dashboard/sum.png" width="600" style="margin: 10px 0;" />
                </li>
                <li>
                    所有物料選擇的部分，皆改為可以多選
                    <br>
                    <img src="/images/dashboard/selm.png" width="600" style="margin: 10px 0;" />
                </li>
                <li>
                    承上，由於多選原因，已經選的無法直接點選修改，重新選取即可
                    <br>
                    <img src="/images/dashboard/selm2.png" width="600" style="margin: 10px 0;" />
                </li>
            </ul>
        <li>
            2019/10/26
            <ul>
                <li>
                    [銷貨出貨 - 銷貨] 物料模組清單可拖曳編輯順序
                    <br>
                    <img src="/images/dashboard/sort.png" width="500" style="margin: 10px 0;" />
                </li>
            </ul>
        </li>
        <li>
            2019/10/12
            <ul>
                <li>[庫存盤點 - 出入庫] 修正差異顯示 (0.00 > 已修正)</li>
                <li>[銷貨出貨 - 銷貨] 修正銷貨單備註內容換行問題</li>
                <li>
                    [銷貨出貨 - 銷貨] 管銷費用採用模組方式新增，建議開一個管銷費用的模組所有的報價都能用 (如圖)
                    <br>
                    <img src="/images/dashboard/sale1.png" width="500" style="margin: 10px 0;" />
                    <img src="/images/dashboard/sale2.png" width="800" style="margin: 10px 0;" />
                    <img src="/images/dashboard/sale3.png" width="800" style="margin: 10px 0;" />
                </li>
                <li>
                    [銷貨出貨 - 銷貨] 含稅為總報價多5%，因為稅金在所有加總完才計算，所以必須存檔才會更新價錢
                    <br>
                    <img src="/images/dashboard/tax4.png" width="500" style="margin: 10px 0;" />
                    <img src="/images/dashboard/tax5.png" width="500" style="margin: 10px 0;" />
                </li>
            </ul>
        </li>
        <li>
            2019/10/7
            <ul>
                <li>
                    [庫存盤點 - 出入庫] 修正差異處理後顯示異常問題
                    <br>
                    <img src="/images/dashboard/fix.png" width="300" style="margin: 10px 0;" />
                </li>
            </ul>
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
