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
            $material_modules[$key]->material2 = Material_module::encodeMaterials($material_module->materials, true);
        }

        $data['material_modules'] = $material_modules;

        return view('selector.material_module', $data);
    }

    // 選擇客戶
    public function customer(Request $request)
    {
        $category = $request->category ?? '';

        $data['category'] = $category;
        $data['categories'] = Customer::categories();

        if ($category == '') {
            $customers = Customer::where('delete_flag', '0')->get();
        } else {
            $customers = Customer::where('delete_flag', '0')
                ->where('category', $category)->get();
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
            $suppliers = Supplier::where('delete_flag', '0')->get();
        } else {
            $suppliers = Supplier::where('delete_flag', '0')
                ->where('category', $category)->get();
        }
        $data['suppliers'] = $suppliers;

        return view('selector.supplier', $data);
    }

    // 選擇批號
    public function lot(Request $request)
    {
        $lots = Lot::where('is_finished', '!=', 1)->get();
        $data['lots'] = $lots;

        return view('selector.lot', $data);
    }
}
