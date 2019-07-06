<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Material_category;
use App\Model\Material_unit;
use App\Model\Material;

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

    // 選擇客戶
    public function customer(Request $request)
    {
        $category = $request->category ?? '';

        $data['categories'] = Customer::categories();
        $data['customers'] = Customer::all();

        return view('selector.customer', $data);
    }
}
