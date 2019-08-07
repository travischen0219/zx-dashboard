<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Material_category extends Model
{
    static public function allWithCode()
    {
        $categories = Material_category::where('delete_flag', 0)->orderBy('orderby', 'asc')->get();

        $data = [];
        foreach ($categories as $category) {
            $data[$category['code']] = $category;
        }

        return $data;
    }
}
