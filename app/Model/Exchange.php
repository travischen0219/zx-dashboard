<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    public function customer_name()
    {
        return $this->hasOne(Customer::class, 'id', 'customer');
    }

    public function sale_name()
    {
        return $this->hasOne(Sale::class, 'id', 'sale_id');        
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
