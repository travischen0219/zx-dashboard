<?php

namespace App\Model;

use App\Model\Material;
use App\Model\Warehouse;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function in()
    {
        return $this->hasOne(In::class, 'id', 'in_id');
    }

    public function material()
    {
        return $this->hasOne(Material::class, 'id', 'material_id');
    }

}
