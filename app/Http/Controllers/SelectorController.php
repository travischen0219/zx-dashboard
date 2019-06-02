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
        $code = $request->code ?? '';

        $data['code'] = $code;
        $data['categories'] = Material_category::allWithCode();
        $data['units'] = Material_unit::allWithKey();
        $data['materials'] = Material::allWithUnit($code);

        return view('selector.material', $data);
    }
}
