<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class In extends Model
{
    use SoftDeletes;

    static public function statuses()
    {
        $data = [];

        $data[10] = '詢價中';
        $data[20] = '已採購';
        $data[30] = '轉加工';
        $data[40] = '轉入庫';
        $data[50] = '已取消';

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
}
