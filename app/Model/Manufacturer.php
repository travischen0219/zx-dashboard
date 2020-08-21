<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
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
        // $manufacturers = Manufacturer::all();
        $manufacturers = Supplier::where('manufacturer', 1)->get();
        
        $data = [];
        foreach ($manufacturers as $manufacturer) {
            $data[$manufacturer['id']] = $manufacturer;
        }

        return $data;
    }
}
