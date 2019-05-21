<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Supplier;
use App\Model\Buy;
use App\Model\Material;
use App\Model\Material_unit;

class PrintController extends Controller
{
    public function buy(Request $request)
    {
        // 參數：年
        $year = $request->year ?? date('Y', strtotime('-1 month'));
        $data["year"] = $year;

        // 參數：月
        $month = $request->month ?? date('m', strtotime('-1 month'));
        $data["month"] = $month;

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

        $buys = Buy::where('delete_flag', '0')
            ->whereIn('status', [2, 3, 4, 11]);

        if ($year != '') {
            $buys->whereYear('buyDate', $year);
        }

        if ($month != 'all') {
            $buys->whereMonth('buyDate', $month);
        }

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

        return view('print.buy', $data);
    }

    public function buy_detail(Request $request)
    {
        $id = $request->id ?? 0;
        if ($id == 0) exit();

        $buy = Buy::find($id);
        if (!$buy) exit();

        $materials = unserialize($buy->materials);

        $buy->count = count($materials['material']);

        $array = [];
        for($i = 0; $i < count($materials['material']); $i++) {
            $material = Material::find($materials['material'][$i]);
            $unit = Material_unit::find($material->unit);

            $array[] = [
                'id' => $material->id,
                'code' => $material->fullCode,
                'name' => $material->fullName,
                'calAmount' => $materials['materialCalAmount'][$i],
                'amount' => $materials['materialAmount'][$i],
                'price' => (float) $materials['materialPrice'][$i],
                'unit' =>  $unit->name
            ];
        }

        $buy->materials = $array;

        $supplier = Supplier::find($buy->supplier);

        $data = [];
        $data['buys'][0]['buy'] = $buy;
        $data['buys'][0]['supplier'] = $supplier;

        return view('print.buy_detail', $data);
    }

    public function buy_details(Request $request)
    {
        $ids = $request->ids ? explode(',', $request->ids) : [];
        if (count($ids) == 0) exit();

        $data = [];
        foreach($ids as $key => $id) {
            $buy = Buy::find($id);
            if (!$buy) exit();

            $materials = unserialize($buy->materials);

            $buy->count = count($materials['material']);

            $array = [];
            for($i = 0; $i < count($materials['material']); $i++) {
                $material = Material::find($materials['material'][$i]);
                $unit = Material_unit::find($material->unit);

                $array[] = [
                    'id' => $material->id,
                    'code' => $material->fullCode,
                    'name' => $material->fullName,
                    'calAmount' => $materials['materialCalAmount'][$i],
                    'amount' => $materials['materialAmount'][$i],
                    'price' => (float) $materials['materialPrice'][$i],
                    'unit' =>  $unit->name
                ];
            }

            $buy->materials = $array;

            $supplier = Supplier::find($buy->supplier);

            $data['buys'][$key]['buy'] = $buy;
            $data['buys'][$key]['supplier'] = $supplier;
        }

        return view('print.buy_detail', $data);
    }
}
