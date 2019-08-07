<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    static public function statuses()
    {
        $data = [];

        $data[1] = '盤點中';
        $data[2] = '已盤點';

        return $data;
    }

    public function percent()
    {
        $inventoryRecordAll = InventoryRecord::where('inventory_id', $this->id)->count();
        $inventoryRecord = InventoryRecord::where('inventory_id', $this->id)->whereNotNull('physical_inventory')->count();

        return round($inventoryRecord / $inventoryRecordAll * 100, 2);
    }

    public function category()
    {
        return $this->hasOne(Material_category::class, 'id', 'category_id');
    }
}
