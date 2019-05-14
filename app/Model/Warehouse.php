<?php

namespace App\Model;

use App\Model\Warehouse_category;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    public function warehouse_category()
    {
        return $this->hasOne(Warehouse_category::class, 'id', 'category');
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
