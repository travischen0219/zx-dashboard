<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    static public function categories()
    {
        $data = [];

        $data[1] = '北部';
        $data[2] = '中部';
        $data[3] = '南部';
        $data[4] = '海外';
        $data[5] = '中國大陸';

        return $data;
    }
}
