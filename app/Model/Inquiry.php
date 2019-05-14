<?php

namespace App\Model;

use App\Model\Supplier;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    public function supplier_name()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier');
    }

}
