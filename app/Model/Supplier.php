<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    static public function categories()
    {
        $data = [];

        $data[1] = '常用';
        $data[2] = '不常用';

        return $data;
    }

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
