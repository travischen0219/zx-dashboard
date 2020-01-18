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
                    'cost' => $request->material_module_cost[$i],
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
            $data[$i]['cost'] = $material_module['cost'] ?? 0;
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

    // 物料模組清單轉庫存
    static public function storeToStock($record, $way = 0, $type = 0)
    {
        $data = [];
        $class = get_class($record);

        $material_modules = unserialize($record->material_modules);

        foreach($material_modules as $material_module) {
            $ms = Material_module::find($material_module['id']);

            $materials = unserialize($ms->materials);

            // 檢查是否出貨超過剩餘庫存
            foreach($materials as $material) {
                // 增加庫存紀錄
                $stock = new Stock;
                $m = Material::find($material['id']);

                if (!$m) dd($m);

                $stock->lot_id = $record->lot_id ?? 0;
                $stock->in_id = ($class == 'App\Model\In' ? $record->id : 0);
                $stock->out_id = ($class == 'App\Model\Out' ? $record->id : 0);
                $stock->way = $way;
                $stock->type = $type;
                $stock->material_id = $material['id'];
                $stock->supplier_id = $record->supplier_id ?? 0;
                $stock->customer_id = $record->customer_id ?? 0;
                $stock->amount = $material['amount'] * $material_module['amount'];
                $stock->amount_before = $m->stock;

                if ($way == 1) {
                    $stock->amount_after = $stock->amount_before + $stock->amount;
                } elseif ($way == 2) {
                    $stock->amount_after = $stock->amount_before - $stock->amount;
                }

                if ($stock->amount_after < 0) {
                    return false;
                }
            }

            foreach($materials as $material) {
                // 增加庫存紀錄
                $stock = new Stock;
                $m = Material::find($material['id']);

                if (!$m) dd($m);

                $stock->lot_id = $record->lot_id ?? 0;
                $stock->in_id = ($class == 'App\Model\In' ? $record->id : 0);
                $stock->out_id = ($class == 'App\Model\Out' ? $record->id : 0);
                $stock->way = $way;
                $stock->type = $type;
                $stock->material_id = $material['id'];
                $stock->supplier_id = $record->supplier_id ?? 0;
                $stock->customer_id = $record->customer_id ?? 0;
                $stock->amount = $material['amount'] * $material_module['amount'];
                $stock->amount_before = $m->stock;

                if ($way == 1) {
                    $stock->amount_after = $stock->amount_before + $stock->amount;
                } elseif ($way == 2) {
                    $stock->amount_after = $stock->amount_before - $stock->amount;
                }

                $stock->stock_date = date('Y-m-d');
                $stock->memo = '';

                $stock->save();

                // 更新物料庫存
                $m->stock = $stock->amount_after;
                $m->save();
            }
        }

        return true;
    }
}
