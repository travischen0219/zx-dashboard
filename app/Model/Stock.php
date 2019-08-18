<?php

namespace App\Model;

use App\Model\Material;
use App\Model\Warehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;

    static public function ways()
    {
        $data = [];

        $data[0] = '全部';
        $data[1] = '入庫';
        $data[2] = '出庫';

        return $data;
    }

    static public function types($way)
    {
        $data = [];

        if ($way == 1) {
            // 入庫：可手動 1 3 15
            $data[0] = '全部';
            $data[1] = '一般入庫';
            $data[2] = '採購轉入庫';
            $data[3] = '銷貨 - 退貨入庫';
            $data[10] = '盤點 - 快速修正入庫';
            $data[12] = '盤點 - 差異處理入庫';
            $data[15] = '加工完成入庫';
        } elseif ($way == 2) {
            // 出庫
            $data[0] = '全部';
            $data[1] = '一般出庫';
            $data[2] = '銷售轉出庫';
            $data[3] = '採購 - 退貨出庫';
            $data[10] = '盤點 - 快速修正出庫';
            $data[12] = '盤點 - 差異處理出庫';
            $data[15] = '加工出庫';
        }

        return $data;
    }

    public function in()
    {
        return $this->hasOne(In::class, 'id', 'in_id');
    }

    public function lot()
    {
        return $this->hasOne(Lot::class, 'id', 'lot_id');
    }

    public function material()
    {
        return $this->hasOne(Material::class, 'id', 'material_id');
    }

}
