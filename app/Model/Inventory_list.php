<?php

namespace App\Model;

use App\Model\Material;
use Illuminate\Database\Eloquent\Model;

class Inventory_list extends Model
{
    public function material_name()
    {
        return $this->hasOne(Material::class, 'id', 'material_id');
    }

    public function warehouse_name()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse_id');
    }
}
