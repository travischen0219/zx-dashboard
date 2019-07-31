<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryRecord extends Model
{
    use SoftDeletes;

    public function material()
    {
        return $this->hasOne(Material::class, 'id', 'material_id');
    }
}
