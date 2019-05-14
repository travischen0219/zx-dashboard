<?php

namespace App\Model;

use App\Model\Customer;
use Illuminate\Database\Eloquent\Model;

class Apply_out_stock extends Model
{
    public function customer_name()
    {
        return $this->hasOne(Customer::class, 'id', 'customer');
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
