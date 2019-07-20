<?php

namespace App\Model;

use App\Model\Material;
use App\Model\Warehouse;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    static public function types()
    {
        $data = [];

        $data[0] = '全部';
        $data[1] = '一般入庫';
        $data[2] = '採購轉入庫';
        $data[3] = '退貨入庫';

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
