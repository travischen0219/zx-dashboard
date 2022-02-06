<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutNotify extends Model
{
    use HasFactory;

    public static function unread()
    {
        return self::where('view', 0)->count();
    }

    public function out()
    {
        return $this->hasOne(Out::class, 'id', 'out_id');
    }
}
