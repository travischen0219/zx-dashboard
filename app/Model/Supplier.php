<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    static public function allWithKey()
    {
        $suppliers = Supplier::all();

        $data = [];
        foreach ($suppliers as $supplier) {
            $data[$supplier['id']] = $supplier;
        }

        return $data;
    }
}
