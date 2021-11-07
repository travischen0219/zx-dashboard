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
        'cost',
        'created_user'
    ];


    static public function allWithKey()
    {
        // $lots = Lot::where('is_finished', '!=', 1)->get();
        $lots = Lot::all();

        $data = [];
        foreach ($lots as $lot) {
            $data[$lot['id']] = $lot;
        }

        return $data;
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
