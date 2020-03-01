<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Material_category;
use App\Model\Material_unit;
use App\Model\Material;
use App\Model\Material_module;
use App\Model\Customer;
use App\Model\Lot;
use App\Model\Supplier;
use App\Model\Manufacturer;
use App\Model\Stock;

class SelectorController extends Controller
{
    public function material(Request $request)
    {
        $idx = $request->idx ?? '';
        $code = $request->code ?? '';

        $data['idx'] = $idx;
        $data['code'] = $code;
        $data['categories'] = Material_category::allWithCode();
        $data['units'] = Material_unit::allWithKey();
        $data['materials'] = Material::allWithUnit($code);

        return view('selector.material', $data);
    }

    public function material_module(Request $request)
    {
        $material_modules = Material_module::all();

        foreach ($material_modules as $key => $material_module) {
            $material_modules[$key]->material = Material::appendMaterials($material_module->materials, true);
        }

        $data['material_modules'] = $material_modules;
        $data['idx'] = $request->idx ?? -1;

        return view('selector.material_module', $data);
    }

    // 選擇客戶
    public function customer(Request $request)
    {
        $category = $request->category ?? '';

        $data['category'] = $category;
        $data['categories'] = Customer::categories();

        if ($category == '') {
            $customers = Customer::all();
        } else {
            $customers = Customer::where('category', $category)->get();
        }
        $data['customers'] = $customers;

        return view('selector.customer', $data);
    }

    // 選擇供應商
    public function supplier(Request $request)
    {
        $category = $request->category ?? '';

        $data['category'] = $category;
        $data['categories'] = Supplier::categories();

        if ($category == '') {
            $suppliers = Supplier::all();
        } else {
            $suppliers = Supplier::where('category', $category)->get();
        }
        $data['suppliers'] = $suppliers;

        return view('selector.supplier', $data);
    }

    // 選擇加工廠商
    public function manufacturer(Request $request)
    {
        $category = $request->category ?? '';

        $data['category'] = $category;
        $data['categories'] = Manufacturer::categories();

        if ($category == '') {
            $manufacturers = Manufacturer::all();
        } else {
            $manufacturers = Manufacturer::where('category', $category)->get();
        }
        $data['manufacturers'] = $manufacturers;

        return view('selector.manufacturer', $data);
    }

    // 選擇批號
    public function lot(Request $request)
    {
        $lots = Lot::where('is_finished', '!=', 1)->get();
        $data['lots'] = $lots;

        return view('selector.lot', $data);
    }

    // 入庫紀錄
    public function in_stock_records(Request $request)
    {
        $id = $request->id ?? 0;
        $stocks = Stock::where('in_id', $id)->get();
        $data['stocks'] = $stocks;
        $data['ways'] = Stock::ways();
        $data['types1'] = Stock::types(1);

        return view('selector.stock_records', $data);
    }

    // 出庫紀錄
    public function out_stock_records(Request $request)
    {
        $id = $request->id ?? 0;
        $stocks = Stock::where('out_id', $id)->get();
        $data['stocks'] = $stocks;
        $data['ways'] = Stock::ways();
        $data['types1'] = Stock::types(1);
        $data['types2'] = Stock::types(2);

        return view('selector.stock_records', $data);
    }

    // 物料庫存紀錄
    public function material_stock_records(Request $request)
    {
        $id = $request->id ?? 0;
        $stocks = Stock::where('material_id', $id)->get();
        $data['stocks'] = $stocks;
        $data['ways'] = Stock::ways();
        $data['types1'] = Stock::types(1);
        $data['types2'] = Stock::types(2);

        return view('selector.stock_records', $data);
    }
}
