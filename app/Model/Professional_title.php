<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Professional_title extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    static public function max_orderby()
    {
        $max_orderby = Professional_title::orderBy('orderby', 'DESC')->first();

        return $max_orderby ? $max_orderby->orderby : 0;
    }

}
