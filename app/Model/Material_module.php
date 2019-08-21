<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material_module extends Model
{
    use SoftDeletes;

    public function image_1()
    {
        return $this->hasOne(Gallery::class, 'id', 'file_1');
    }
    public function image_2()
    {
        return $this->hasOne(Gallery::class, 'id', 'file_2');
    }
    public function image_3()
    {
        return $this->hasOne(Gallery::class, 'id', 'file_3');
    }

    public function file1()
    {
        return $this->hasOne(StorageFile::class, 'id', 'file_1');
    }
    public function file2()
    {
        return $this->hasOne(StorageFile::class, 'id', 'file_2');
    }
    public function file3()
    {
        return $this->hasOne(StorageFile::class, 'id', 'file_3');
    }

    // 停用
    static public function encodeMaterials($materials, $php = false)
    {
        $data = [];
        $materials = unserialize($materials);
        $count = count($materials['material']);

        for ($i = 0; $i < $count; $i++) {
            $m = Material::find($materials['material'][$i]);
            $data[$i]['id'] = $materials['material'][$i] ?? 0;
            $data[$i]['code'] = $m->fullCode;
            $data[$i]['name'] = $m->fullName;
            $data[$i]['unit'] = $m->unit;
            $data[$i]['amount'] = $materials['materialAmount'][$i] ?? 0;
            $data[$i]['cost'] = $materials['materialCost'][$i] ?? 0;
            $data[$i]['price'] = $materials['materialPrice'][$i] ?? 0;
            $data[$i]['cal_unit'] = $materials['materialCalUnit'][$i] ?? 0;
            $data[$i]['cal_price'] = $materials['materialCalPrice'][$i] ?? 0;
        }

        if ($php) {
            return $data;
        } else {
            $data = json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);
            return $data;
        }
    }

    // 打包物料模組
    static public function packMaterialModules($request)
    {
        // 打包物料模組 (不存單位、不存是否有計價)
        $materialModules = [];
        if (isset($request->material_module)) {
            for ($i = 0; $i < count($request->material_module); $i++) {
                $materialModules[] = [
                    'id' => $request->material_module[$i],
                    'code' => $request->material_module_code[$i],
                    'name' => $request->material_module_name[$i],
                    'amount' => $request->material_module_amount[$i],
                    'price' => $request->material_module_price[$i]
                ];
            }
        }

        return serialize($materialModules);
    }

    // 解包物料模組清單
    static public function appendMaterialModules($material_modules, $php = false)
    {
        $data = [];
        $material_modules = unserialize($material_modules);

        $i = 0;
        foreach ($material_modules as $material_module) {
            $data[$i]['id'] = $material_module['id'] ?? 0;
            $data[$i]['code'] = $material_module['code'] ?? '';
            $data[$i]['name'] = $material_module['name'] ?? '';

            $data[$i]['amount'] = $material_module['amount'] ?? 0;
            $data[$i]['total_cost'] = $material_module['total_cost'] ?? 0;
            $data[$i]['price'] = $material_module['price'] ?? 0;

            $i++;
        }

        if ($php) {
            return $data;
        } else {
            $data = json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);
            return $data;
        }
    }
}
