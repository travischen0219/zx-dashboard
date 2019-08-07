<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    static public function categories()
    {
        $data = [];

        $data[1] = '常用';
        $data[2] = '不常用';

        return $data;
    }

    static public function allWithKey()
    {
        $manufacturers = Manufacturer::all();

        $data = [];
        foreach ($manufacturers as $manufacturer) {
            $data[$manufacturer['id']] = $manufacturer;
        }

        return $data;
    }
}
