<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Transfer_inventory extends Model
{
    public function o_warehouse_name()
    {
        return $this->hasOne(Warehouse::class, 'id', 'original_warehouse');
    }

    public function n_warehouse_name()
    {
        return $this->hasOne(Warehouse::class, 'id', 'new_warehouse');
    }

    public function material_name()
    {
        return $this->hasOne(Material::class, 'id', 'material_id');
    }
}
