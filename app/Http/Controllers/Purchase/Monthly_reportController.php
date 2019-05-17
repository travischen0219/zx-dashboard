<?php

namespace App\Http\Controllers\Purchase;

use App\Model\User;
use App\Model\Material;
use App\Model\Supplier;
use App\Model\Buy;
use Illuminate\Http\Request;
use App\Model\Account_payable;
use App\Http\Controllers\Controller;

class Monthly_reportController extends Controller
{
    public function print(Request $request)
    {
        $data = [];

        // 參數：開始日期
        $startDate = $request->startDate ?? date('Y/m/1', strtotime('-1 month'));
        $data["startDate"] = $startDate;

        // 參數：結束日期
        $endDate = $request->endDate ?? date('Y/m/t', strtotime('-1 month'));
        $data["endDate"] = $endDate;

        // 參數：批號
        $lot_number = $request->lot_number ?? '';
        $data["lot_number"] = $lot_number;

        // 參數：供應商
        $supplierID = $request->supplierID ?? '';
        $data["supplierID"] = $supplierID;

        // 參數：欄位選擇
        $data['selColumns'] = $request->selColumns ?? [0, 1, 2, 3, 4, 5, 6, 7, 8];

        // 全部供應商
        $suppliers = Supplier::allWithKey();
        $data["suppliers"] = $suppliers;

        // 全部欄位
        $data['columns'] = ['項次', '批號', '廠商', '編號', '品名', '採購數量', '進貨數量', '單價', '金額'];

        // 採購資料
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));

        $buys = Buy::where('delete_flag', '0')
            ->whereIn('status', [2, 3, 4, 11])
            ->whereBetween('buyDate', [$startDate, $endDate]);

        if ($lot_number != '') {
            $buys->where('lot_number', $lot_number);
        }

        if ($supplierID != '') {
            $buys->where('supplier', $supplierID);
        }

        $buys = $buys->get();

        foreach ($buys as $key => $buy) {
            $materials = unserialize($buy->materials);

            $buys[$key]->count = count($materials['material']);

            $array = [];
            for($i = 0; $i < count($materials['material']); $i++) {
                $material = Material::find($materials['material'][$i]);
                $array[] = [
                    'id' => $material->id,
                    'code' => $material->fullCode,
                    'name' => $material->fullName,
                    'calAmount' => $materials['materialCalAmount'][$i],
                    'amount' => $materials['materialAmount'][$i],
                    'price' => $materials['materialPrice'][$i]
                ];
            }

            $buys[$key]->materials = $array;
        }

        $data["buys"] = $buys;

        return view('purchase.monthly_report.print', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_code = 'all';
        $account_payables = Account_payable::where('delete_flag','0')->get();
        return view("purchase.monthly_report.show",compact('account_payables','search_code'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
