<?php

namespace App\Model;

use App\Model\Material;
use App\Model\Warehouse;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public function warehouse_name()
    {
        return $this->hasOne(Warehouse::class, 'id', 'warehouse');
    }

    public function material_name()
    {
        return $this->hasOne(Material::class, 'id', 'material');
    }

    public function  user_name()
    {
        return $this->hasOne(User::class, 'id', 'created_user');
    }

    public function supplier_name()
    {
        return $this->hasOne(Supplier::class, 'id', 'supplier');
    }

    public function customer_name()
    {
        return $this->hasOne(Customer::class, 'id', 'customer');
    }
}
