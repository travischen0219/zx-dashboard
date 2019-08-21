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
        $data[20] = '新訂單';
        $data[30] = '申請出庫';
        $data[35] = '集貨撿貨';
        $data[40] = '轉出庫';
        $data[50] = '已取消';

        return $data;
    }

    static public function pay_statuses()
    {
        $data = [];

        $data[1] = '收清';
        $data[2] = '未收清';

        return $data;
    }

    public function lot()
    {
        return $this->hasOne(Lot::class, 'id', 'lot_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }

    public function total_cost()
    {
        return Material_module::getTotalCost($this->material_modules);
    }

    public function total_price()
    {
        return Material_module::getTotalPrice($this->material_modules);
    }

    public function total_pay()
    {
        return Pay::getTotalPay($this->pays);
    }
}
