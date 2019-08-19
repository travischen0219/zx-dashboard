<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\In;
use App\Model\Material;

class ProcessController extends Controller
{

    public function index ()
    {
        $ins = In::whereIn('status', [30, 35])->get();

        foreach ($ins as $in) {
            $rawMaterials = Material::appendMaterials($in->materials, true);

            foreach ($rawMaterials as $key => $rawMaterial) {
                $rawMaterials[$key]['model'] = Material::find($rawMaterial['id']);
            }
            $in->materials = $rawMaterials;
        }

        $data = [];
        $data['ins'] = $ins;

        return view('stock.process.index', $data);
    }
}
