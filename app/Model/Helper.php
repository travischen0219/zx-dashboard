<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    static public function arrayAppendKey($array, $col = 'id')
    {
        $data = [];
        foreach ($array as $key => $value) {
            $data[$value->$col] = $value;
        }

        return $data;
    }
}
