<?php

namespace App\Http\Controllers\Purchase;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\In;
use App\Model\Material;

class OnController extends Controller
{

    public function index ()
    {
        // $ins = In::where('status', 20)->get();
        $ins = In::whereIn('status', [20, 30])->get();
        $ms = [];

        foreach ($ins as $in) {
            $rawMaterials = Material::appendMaterials($in->materials, true);

            foreach ($rawMaterials as $rawMaterial) {
                $ms[$rawMaterial['id']]['amounts'][] = $rawMaterial['amount'];
                $ms[$rawMaterial['id']]['ins'][] = $in;
                $ms[$rawMaterial['id']]['material'] = Material::find($rawMaterial['id']);
            }
        }

        $data = [];
        $data['ms'] = $ms;

        return view('purchase.on.index', $data);
    }

    public function in ()
    {
        $ins = In::whereIn('status', [20, 30])->get();

        foreach ($ins as $in) {
            $rawMaterials = Material::appendMaterials($in->materials, true);

            foreach ($rawMaterials as $key => $rawMaterial) {
                $rawMaterials[$key]['model'] = Material::find($rawMaterial['id']);
            }
            $in->materials = $rawMaterials;
        }

        $data = [];
        $data['ins'] = $ins;

        return view('purchase.on.in', $data);
    }
}
