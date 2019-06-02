<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Material_module extends Model
{
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

    static public function encodeMaterials2($materials2)
    {
        $materials2 = unserialize($materials2);

        foreach($materials2 as $key => $material2) {
            $material = Material::find($material2['id']);
            $materials2[$key]['code'] = $material->fullCode;
            $materials2[$key]['name'] = $material->fullName;
            $materials2[$key]['unit'] = $material->unit;
        }

        $materials2 = json_encode($materials2, JSON_HEX_QUOT | JSON_HEX_TAG);

        return $materials2;
    }
}
