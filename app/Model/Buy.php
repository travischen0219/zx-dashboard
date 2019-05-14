<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Buy extends Model
{
    public function supplier_name()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier');
    }
}
