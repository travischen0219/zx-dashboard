<?php

namespace App\Model;

use App\Model\Gallery;
use App\Model\Warehouse;
use App\Model\Material_unit;
use App\Model\Material_category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    public function material_category_name()
    {
        return $this->hasOne(Material_category::class, 'code', 'material_categories_code');
    }

    public function material_unit_name()
    {
        return $this->hasOne(Material_unit::class, 'id', 'unit');
    }

    public function warehouse_name()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse');
    }

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

    static public function allWithUnit($code)
    {
        $materials = Material::where('delete_flag','0')
            ->where('status','1')
            ->where('unit','<>','0');

        if ($code != '') {
            $materials = $materials->where('material_categories_code', $code);
        }

        $materials = $materials->orderBy('fullCode', 'asc')->get();

        return $materials;
    }

    // 解包物料清單
    static public function appendMaterials($materials, $php = false)
    {
        $data = [];
        $materials = unserialize($materials);

        $i = 0;
        foreach ($materials as $material) {
            $m = Material::find($material['id']);

            if (!$m) {
                return false;
            }

            $data[$i]['id'] = $material['id'] ?? 0;
            $data[$i]['code'] = $m->fullCode;
            $data[$i]['name'] = $m->fullName;
            $data[$i]['cal'] = $m->material_category_name->cal;
            $data[$i]['stock'] = $m->stock;

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
        $materials = unserialize($materials);

        $total_cost = 0;
        foreach($materials as $material) {
            $m = Material::find($material['id']);

            if ($m->material_category_name->cal == 1) {
                $amount = $material['buy_amount'] ?? 0;
                $cost = $material['cal_price'] ?? 0;
                $total_cost += ($amount * $cost);
            } else {
                $amount = $material['amount'] ?? 0;
                $cost = $material['cost'] ?? 0;
                $total_cost += ($amount * $cost);
            }
        }

        return $total_cost;
    }

    // 物料計價總成本
    static public function getTotalCal($materials)
    {
        $data = [];
        $materials = unserialize($materials);

        $total_cal = 0;
        foreach($materials as $material) {
            $amount = $material['buy_amount'] ?? 0;
            $cost = $material['cal_price'] ?? 0;
            $total_cal += ($amount * $cost);
        }


        return $total_cal;
    }

    // 物料總售價
    static public function getTotalPrice($materials)
    {
        $data = [];
        $materials = unserialize($materials);

        $total_price = 0;
        foreach ($materials as $material) {
            $amount = $material['amount'] ?? 0;
            $price = $material['price'] ?? 0;
            $total_price += ($amount * $price);
        }

        return $total_price;
    }

    // 物料清單轉庫存
    static public function storeToStock($record, $way = 0, $type = 0)
    {
        $data = [];
        $class = get_class($record);
        $materials = unserialize($record->materials);

        foreach($materials as $material) {
            // 增加庫存紀錄
            $stock = new Stock;
            $m = Material::find($material['id']);

            $stock->lot_id = $record->lot_id ?? 0;
            $stock->in_id = ($class == 'App\Model\In' ? $record->id : 0);
            $stock->out_id = ($class == 'App\Model\Out' ? $record->id : 0);
            $stock->way = $way;
            $stock->type = $type;
            $stock->material_id = $material['id'];
            $stock->supplier_id = $record->supplier_id ?? 0;
            $stock->customer_id = $record->customer_id ?? 0;
            $stock->amount = $material['amount'];
            $stock->amount_before = $m->stock;

            if ($way == 1) {
                $stock->amount_after = $stock->amount_before + $material['amount'];
            } elseif ($way == 2) {
                $stock->amount_after = $stock->amount_before - $material['amount'];
            }

            $stock->stock_date = date('Y-m-d');
            $stock->memo = '';

            $stock->save();

            // 更新物料庫存
            $m->stock = $stock->amount_after;
            $m->save();
        }

        return true;
    }

    // 最後物料編號
    static public function lastFullCode($material_categories_code = '')
    {
        if ($material_categories_code == '') {
            return Material::orderBy('created_at', 'desc')->first()->fullCode ?? '無';
        } else {
            return Material::where('material_categories_code', $material_categories_code)->orderBy('created_at', 'desc')->first()->fullCode ?? '無';
        }
        
    }
}
