<?php

namespace App\Model;


use App\Model\Warehouse_category;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    public function warehouse_category_name()
    {
        return $this->hasOne(Warehouse_category::class, 'id', 'warehouse_category');
    }
}
