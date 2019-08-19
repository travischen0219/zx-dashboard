<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Out extends Model
{
    use SoftDeletes;

    static public function statuses()
    {
        $data = [];

        $data[10] = '報價中';
        $data[20] = '已採購';
        $data[30] = '轉加工';
        $data[35] = '加工完成';
        $data[40] = '轉入庫';
        $data[50] = '已取消';

        return $data;
    }

    static public function pay_statuses()
    {
        $data = [];

        $data[1] = '付清';
        $data[2] = '未付清';

        return $data;
    }

    public function lot()
    {
        return $this->hasOne(Lot::class, 'id', 'lot_id');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier_id');
    }

    public function manufacturer()
    {
        return $this->hasOne(Manufacturer::class, 'id', 'manufacturer_id');
    }

    public function total_cost()
    {
        return Material::getTotalCost($this->materials);
    }

    public function total_pay()
    {
        return Pay::getTotalPay($this->pays);
    }
}
