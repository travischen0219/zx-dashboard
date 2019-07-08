<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material_module extends Model
{
    use SoftDeletes;

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

    static public function encodeMaterials($materials, $php = false)
    {
        $data = [];
        $materials = unserialize($materials);
        $count = count($materials['material']);

        for($i = 0; $i < $count; $i++) {
            $m = Material::find($materials['material'][$i]);
            $data[$i]['id'] = $materials['material'][$i] ?? 0;
            $data[$i]['code'] = $m->fullCode;
            $data[$i]['name'] = $m->fullName;
            $data[$i]['unit'] = $m->unit;
            $data[$i]['amount'] = $materials['materialAmount'][$i] ?? 0;
            $data[$i]['cost'] = $materials['materialCost'][$i] ?? 0;
            $data[$i]['price'] = $materials['materialPrice'][$i] ?? 0;
            $data[$i]['cal_unit'] = $materials['materialCalUnit'][$i] ?? 0;
            $data[$i]['cal_price'] = $materials['materialCalPrice'][$i] ?? 0;
        }

        if ($php) {
            return $data;
        } else {
            $data = json_encode($data, JSON_HEX_QUOT | JSON_HEX_TAG);
            return $data;
        }
    }
}
