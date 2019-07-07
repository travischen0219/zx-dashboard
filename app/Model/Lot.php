<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'customer_id',
        'start_date',
        'end_date',
        'status',
        'is_finished',
        'memo',
        'created_user'
    ];
}
