<?php

namespace App\Model;

use App\Model\Customer;
use Illuminate\Database\Eloquent\Model;

class Account_receivable extends Model
{
    public function customer_name()
    {
        return $this->hasOne(Customer::class, 'id', 'customer');
    }
}
