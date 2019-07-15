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

        for($i = 0; $i < $count; $i++) {
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

    // 使用
    static public function appendMaterials($materials, $php = false)
    {
        $data = [];
        $materials = unserialize($materials);

        $i = 0;
        foreach($materials as $material) {
            $m = Material::find($material['id']);

            $data[$i]['id'] = $material['id'] ?? 0;
            $data[$i]['code'] = $m->fullCode;
            $data[$i]['name'] = $m->fullName;
            $data[$i]['cal'] = $m->material_category_name->cal;

            $data[$i]['unit'] = $m->unit;
            $data[$i]['cal_unit'] = $m->cal_unit;

            $data[$i]['amount'] = $material['amount'] ?? 0;
            $data[$i]['cost'] = $material['cost'] ?? 0;
            $data[$i]['price'] = $material['price'] ?? 0;

            $data[$i]['cal_amount'] = $material['cal_amount'] ?? 0;
            $data[$i]['cal_price'] = $material['cal_price'] ?? 0;
            $data[$i]['buy_amount'] = $material['buy_amount'] ?? 0;

            $i++;
        }

        if ($php) {
            return $data;
        } else {
            $data = json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);
            return $data;
        }
    }

    // 打包物料清單
    static public function packMaterials($request)
    {
        // 打包物料模組 (不存單位、不存是否有計價)
        $materials = [];
        if (isset($request->material)) {
            for($i = 0; $i < count($request->material); $i++) {
                $materials[] = [
                    'id' => $request->material[$i],
                    'amount' => $request->material_amount[$i],
                    'cost' => $request->material_cost[$i],
                    'price' => $request->material_price[$i],

                    // 計價分類才有
                    'cal_amount' => $request->material_cal_amount[$i] ?? 0,
                    'cal_price' => $request->material_cal_price[$i] ?? 0,
                    'buy_amount' => $request->material_buy_amount[$i] ?? 0
                ];
            }
        }

        return serialize($materials);
    }

    // 物料總成本
    static public function getTotalCost($materials)
    {
        $data = [];
        $materials = unserialize($materials);

        $total_cost = 0;
        foreach($materials as $material) {
            $amount = $material['amount'] ?? 0;
            $cost = $material['cost'] ?? 0;
            $total_cost += ($amount * $cost);
        }

        return $total_cost;
    }

}
