<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class P_exchange extends Model
{
    public function supplier_name()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier');
    }
    public function buy_name()
    {
        return $this->hasOne(Buy::class, 'id', 'buy_id');
    }
}
