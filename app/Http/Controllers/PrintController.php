<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Lot;
use App\Model\Supplier;
use App\Model\Customer;
use App\Model\In;
use App\Model\Out;
use App\Model\Material;
use App\Model\Material_unit;
use App\Model\Material_module;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Model\User;

class PrintController extends Controller
{
    public function in(Request $request)
    {
        // 參數：年
        $year = $request->year ?? date('Y', strtotime('-1 month'));
        $data["year"] = $year;

        // 參數：月
        $month = $request->month ?? date('m', strtotime('-1 month'));
        $data["month"] = $month;

        // 參數：批號
        $lot_id = $request->lot_id ?? 0;
        $data["lot_id"] = $lot_id;

        // 參數：供應商
        $supplier_id = $request->supplier_id ?? 0;
        $data["supplier_id"] = $supplier_id;

        // 參數：欄位選擇
        $data['selColumns'] = $request->selColumns ?? [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

        // 全部批號
        $lots = Lot::allWithKey();
        $data["lots"] = $lots;

        // 全部供應商
        $suppliers = Supplier::allWithKey();
        $data["suppliers"] = $suppliers;

        // 全部欄位
        $data['columns'] = ['項次', '採購日期', '批號', '廠商', '編號', '品名', '採購數量', '進貨數量', '單價', '金額'];

        $ins = In::whereIn('status', [20, 30, 35, 40]);

        if ($year != '') {
            $ins->whereYear('buy_date', $year);
        }

        if ($month != 'all') {
            $ins->whereMonth('buy_date', $month);
        }

        if ($lot_id != 0) {
            $ins->where('lot_id', $lot_id);
        }

        if ($supplier_id != 0) {
            $ins->where('supplier_id', $supplier_id);
        }

        $ins = $ins->get();

        foreach ($ins as $key => $in) {
            $ins[$key]->materials = Material::appendMaterials($in->materials, true);
        }

        $data["ins"] = $ins;
        $data["units"] = Material_unit::allWithKey();

        return view('print.in', $data);
    }

    public function out(Request $request)
    {
        // 參數：年
        $year = $request->year ?? date('Y', strtotime('-1 month'));
        $data["year"] = $year;

        // 參數：月
        $month = $request->month ?? date('m', strtotime('-1 month'));
        $data["month"] = $month;

        // 參數：批號
        $lot_id = $request->lot_id ?? 0;
        $data["lot_id"] = $lot_id;

        // 參數：客戶
        $customer_id = $request->customer_id ?? 0;
        $data["customer_id"] = $customer_id;

        // 參數：欄位選擇
        // $data['selColumns'] = $request->selColumns ?? [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $data['selColumns'] = $request->selColumns ?? [0, 1, 2, 3];

        // 全部批號
        $lots = Lot::allWithKey();
        $data["lots"] = $lots;

        // 全部客戶
        $customers = Customer::allWithKey();
        $data["customers"] = $customers;

        // 全部欄位
        // $data['columns'] = ['項次', '銷貨日期', '批號', '客戶', '編號', '品名', '銷貨數量', '單位成本', '單價', '金額'];
        $data['columns'] = ['批號', '客戶', '總成本', '總金額'];

        $outs = Out::whereIn('status', [20, 30, 35, 40]);

        if ($year != 'all') {
            $outs->whereYear('created_date', $year);
        }

        if ($month != 'all') {
            $outs->whereMonth('created_date', $month);
        }

        if ($lot_id != 0) {
            $outs->where('lot_id', $lot_id);
        }

        if ($customer_id != 0) {
            $outs->where('customer_id', $customer_id);
        }


        $outs = $outs->get();

        $total_cost = 0;
        $total_price = 0;

        foreach ($outs as $key => $out) {
            $outs[$key]->material_modules = Material_module::appendMaterialModules($out->material_modules, true);

            $total_cost += $out->total_cost;
            $total_price += $out->total_price;
        }

        $data["outs"] = $outs;
        $data["total_cost"] = $total_cost;
        $data["total_price"] = $total_price;

        return view('print.out', $data);
    }

    public function in_detail(Request $request)
    {
        $id = $request->id ?? 0;
        if ($id == 0) exit();

        $in = In::find($id);
        if (!$in) exit();

        $in->materials = Material::appendMaterials($in->materials, true);

        $data = [];
        $data['ins'][0]['in'] = $in;

        return view('print.in_detail', $data);
    }

    public function in_detail_excel(Request $request)
    {
        $id = $request->id ?? 0;
        if ($id == 0) exit();

        $in = In::find($id);
        if (!$in) exit();

        $materials = unserialize($in->materials);

        $array = [];

        foreach ($materials as $material) {
            $m = Material::find($material['id']);

            if (!$m) {
                continue;
            }

            $unit = Material_unit::find($m->unit);

            $array[] = [
                'id' => $material['id'],
                'code' => $m->fullCode,
                'name' => $m->fullName,
                'size' => $m->size,
                'color' => $m->color,
                'stock' => $m->stock,
                'memo' => $m->memo,
                'amount' => $material['amount'],
                'cost' => (float) $material['cost'],
                'unit' =>  $unit->name
            ];
        }

        $in->materials = $array;

        $filename = 'P' . $in->code;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 標題
        $sheet->mergeCells("A1:F1");
        $sheet->getStyle('A1')
            ->getFont()
            ->setSize(24);
        $sheet->getStyle('A1')
            ->getAlignment()
            ->setHorizontal('center');
        $sheet->setCellValue('A1', '真心蓮坊股份有限公司採購單');

        // 編號
        $sheet->mergeCells("A2:F2");
        $sheet->getStyle('A2')
            ->getFont()
            ->setSize(12);
        $sheet->setCellValue('A2', '編號：P' . $in->code .
            '    批號：' . $in->lot->code);

        // 編號
        $sheet->mergeCells("A2:F2");
        $sheet->getStyle('A3')
            ->getFont()
            ->setSize(12);
        $sheet->setCellValue('A3', '廠商：' . $in->supplier->shortName .
            '    訂購日期：：' . ($in->buy_date ? date('Y年m月d日', strtotime($in->buy_date)) : '') .
            '    交貨日期：' . ($in->should_arrive_date ? date('Y年m月d日', strtotime($in->should_arrive_date)) : ''));

        // 欄位
        $sheet->getStyle('A4:F4')
            ->getFont()
            ->setSize(16);
        $sheet->setCellValue('A4', '項次');

        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->setCellValue('B4', '貨品編號');
        $sheet->getColumnDimension('C')->setWidth(60);
        $sheet->setCellValue('C4', '品名規格');
        $sheet->setCellValue('D4', '數量');
        $sheet->setCellValue('E4', '單價');
        $sheet->setCellValue('F4', '小計');

        // 內容
        $row = 5;
        foreach ($in->materials as $key => $material) {
            $m = Material::find($material['id']);

            $sheet->getStyle("A$row:F$row")
                ->getFont()
                ->setSize(16);

            $sheet->setCellValue("A$row", $row - 4);
            $sheet->setCellValue("B$row", $m['fullCode']);
            $sheet->setCellValue("C$row", $m['fullName']);
            $sheet->setCellValue("D$row", round($material['amount'], 2));
            $sheet->setCellValue("E$row", round($material['cost'], 2));
            $sheet->setCellValue("F$row", round($material['amount'] * $material['cost'], 2));

            $row++;
        }

        if (count($in->materials) < 19) {
            for ($i = 0; $i < 19 - count($in->materials); $i++) {
                $sheet->getStyle("A$row:F$row")
                ->getFont()
                ->setSize(16);

                $sheet->setCellValue("A$row", '');
                $sheet->setCellValue("B$row", '');
                $sheet->setCellValue("C$row", '');
                $sheet->setCellValue("D$row", '');
                $sheet->setCellValue("E$row", '');
                $sheet->setCellValue("F$row", '');

                $row++;
            }
        }

        // 簽核
        $row++;
        $sheet->mergeCells("A$row:F$row");
        $sheet->getStyle("A$row")
            ->getFont()
            ->setSize(24);
        $sheet->setCellValue("A$row", "董事長：                   總經理：                   經辦人：");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function out_detail(Request $request)
    {
        $id = $request->id ?? 0;
        if ($id == 0) exit();

        $out = Out::find($id);
        if (!$out) exit();

        $out->material_modules = Material_module::appendMaterialModules($out->material_modules, true);

        $data = [];
        $data['outs'][0]['out'] = $out;

        return view('print.out_detail', $data);
    }

    public function in_details(Request $request)
    {
        $ids = $request->ids ? explode(',', $request->ids) : [];
        if (count($ids) == 0) exit();

        $data = [];
        foreach($ids as $key => $id) {
            $in = In::find($id);
            if (!$in) exit();

            $in->materials = Material::appendMaterials($in->materials, true);

            $data['ins'][$key]['in'] = $in;
        }

        return view('print.in_detail', $data);
    }

    public function out_details(Request $request)
    {
        $ids = $request->ids ? explode(',', $request->ids) : [];
        if (count($ids) == 0) exit();

        $data = [];
        foreach($ids as $key => $id) {
            $out = Out::find($id);
            if (!$out) exit();

            $out->material_modules = Material_module::appendMaterialModules($out->material_modules, true);

            $data['outs'][$key]['out'] = $out;
        }

        return view('print.out_detail', $data);
    }

    public function in_unpay(Request $request)
    {
        $data = [];
        $unpaysOrigin = In::whereIn('status', [20, 30, 35, 40])->where('balance', '>', 0)->get();

        $unpays = [];
        foreach ($unpaysOrigin as $unpay) {
            if (!isset($unpays[$unpay["supplier_id"]])) $unpays[$unpay["supplier_id"]] = [];

            array_push($unpays[$unpay["supplier_id"]], $unpay);
        }
        ksort($unpays);
        $data["unpays"] = $unpays;

        $suppliers = Supplier::allWithKey();
        $data["suppliers"] = $suppliers;

        $supplierKeys = array_keys($unpays);
        sort($supplierKeys);
        $data["supplierKeys"] = $supplierKeys;

        return view('print.in_unpay', $data);
    }

    public function out_unpay(Request $request)
    {
        $data = [];
        $unpaysOrigin = Out::whereIn('status', [20, 30, 35, 40])->where('balance', '>', 0)->get();

        $unpays = [];
        foreach ($unpaysOrigin as $unpay) {
            if (!isset($unpays[$unpay["customer_id"]])) $unpays[$unpay["customer_id"]] = [];

            array_push($unpays[$unpay["customer_id"]], $unpay);
        }
        ksort($unpays);
        $data["unpays"] = $unpays;

        $customers = Customer::allWithKey();
        $data["customers"] = $customers;

        $customerKeys = array_keys($unpays);
        sort($customerKeys);
        $data["customerKeys"] = $customerKeys;

        return view('print.out_unpay', $data);
    }

    public function material_module(Request $request)
    {
        $id = $request->id ?? 0;
        if ($id == 0) exit();

        $module = Material_module::find($id);
        if (!$module) exit();

        $materials = unserialize($module->materials);

        $array = [];
        foreach ($materials as $material) {
            $m = Material::find($material['id']);

            if (!$m) {
                return '此模組內的物料已被刪除，請刪除此模組';
            }

            $unit = Material_unit::find($m->unit);

            $array[] = [
                'id' => $material['id'],
                'code' => $m->fullCode,
                'name' => $m->fullName,
                'size' => $m->size,
                'color' => $m->color,
                'stock' => $m->stock,
                'memo' => $m->memo,
                'amount' => $material['amount'],
                'price' => (float) $material['price'],
                'unit' =>  $unit->name
            ];
        }

        $module->materials = $array;

        // 單筆列印
        $data = [];
        $data['id'] = $id;
        $data['modules'][0]['module'] = $module;

        return view('print.material_module', $data);
    }

    public function material_module_excel(Request $request)
    {
        $id = $request->id ?? 0;
        if ($id == 0) exit();

        $module = Material_module::find($id);
        if (!$module) exit();

        $materials = unserialize($module->materials);

        $array = [];
        foreach ($materials as $material) {
            $m = Material::find($material['id']);
            $unit = Material_unit::find($m->unit);

            $array[] = [
                'id' => $material['id'],
                'code' => $m->fullCode,
                'name' => $m->fullName,
                'size' => $m->size,
                'color' => $m->color,
                'stock' => $m->stock,
                'memo' => $m->memo,
                'amount' => $material['amount'],
                'price' => (float) $material['price'],
                'unit' =>  $unit->name
            ];
        }

        $module->materials = $array;

        $data = [];
        $data['id'] = $id;
        $data['modules'][0]['module'] = $module;

        $filename = $module->name;
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 標題
        $sheet->mergeCells("A1:H1");
        $sheet->getStyle('A1')
            ->getFont()
            ->setSize(24);
        $sheet->setCellValue('A1', $module->name);

        // 編號
        $sheet->mergeCells("A2:H2");
        $sheet->getStyle('A2')
            ->getFont()
            ->setSize(24);
        $sheet->setCellValue('A2', '編號：' . $module->code);

        // 欄位
        $sheet->getStyle('A3:H3')
            ->getFont()
            ->setSize(16);
        $sheet->setCellValue('A3', '序號');

        $sheet->getColumnDimension('B')->setWidth(60);
        $sheet->setCellValue('B3', '品名');
        $sheet->setCellValue('C3', '數量');
        $sheet->setCellValue('D3', '單位');
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->setCellValue('E3', '尺寸');
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->setCellValue('F3', '顏色');
        $sheet->setCellValue('G3', '成本');
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->setCellValue('H3', '備註');

        // 內容
        $index = 1;
        $cost_total = 0;
        foreach ($module->materials as $key => $material) {
            $m = Material::find($material['id']);

            $row = $index + 3;
            $sheet->getStyle("A$row:H$row")
                ->getFont()
                ->setSize(16);

            $sheet->setCellValue("A$row", $index);
            $sheet->setCellValue("B$row", $material['code'] . ' ' . $material['name']);
            $sheet->setCellValue("C$row", round($material['amount'], 2));
            $sheet->setCellValue("D$row", $material['unit']);
            $sheet->setCellValue("E$row", $material['size']);
            $sheet->setCellValue("F$row", $material['color']);
            $sheet->setCellValue("G$row", round($m->cost, 2));
            $sheet->setCellValue("H$row", $material['memo']);

            $index++;
            $cost_total += ($m->cost * $material['amount']);
        }

        // 總和
        $row++;
        $sheet->mergeCells("A$row:H$row");
        $sheet->getStyle("A$row")
            ->getFont()
            ->setSize(24);
        $sheet->getStyle("A$row")
            ->getAlignment()
            ->setHorizontal('right');
        $sheet->setCellValue("A$row", '成本合計：' . round($cost_total, 2));

        // 簽核
        $row++;
        $sheet->mergeCells("A$row:H$row");
        $sheet->getStyle("A$row")
            ->getFont()
            ->setSize(24);
        $sheet->setCellValue("A$row", "董事長：                         總經理：                         經辦人：");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }

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
}
