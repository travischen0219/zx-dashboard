<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Process_function extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    static public function max_orderby()
    {
        $max_orderby = Process_function::orderBy('orderby', 'DESC')->first();

        return $max_orderby ? $max_orderby->orderby : 0;
    }
}
