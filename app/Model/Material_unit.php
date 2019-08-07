<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Material_unit extends Model
{
    static public function allWithKey()
    {
        $units = Material_unit::where('delete_flag', 0)->orderBy('orderby', 'asc')->get();

        $data = [];
        foreach ($units as $unit) {
            $data[$unit['id']] = $unit;
        }

        return $data;
    }
}
