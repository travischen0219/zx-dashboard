<?php

namespace App\Model;

use App\Model\Gallery;
use App\Model\Warehouse;
use App\Model\Material_unit;
use App\Model\Material_category;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    public function material_category_name()
    {
        return $this->hasOne(Material_category::class, 'code', 'material_categories_code');
    }

    public function material_unit_name()
    {
        return $this->hasOne(Material_unit::class, 'id', 'unit');
    }

    public function warehouse_name()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse');
    }

    public function image_1()
    {
        return $this->hasOne(Gallery::class, 'id', 'file_1');
    }
    public function image_2()
    {
        return $this->hasOne(Gallery::class, 'id', 'file_2');
    }
    public function image_3()
    {
        return $this->hasOne(Gallery::class, 'id', 'file_3');
    }
}
