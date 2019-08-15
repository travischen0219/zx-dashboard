<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    static public function max_orderby() {
        $max_orderby = Department::orderBy('orderby', 'DESC')->first();

        return $max_orderby ? $max_orderby->orderby : 0;
    }
}
