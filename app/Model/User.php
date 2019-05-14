<?php

namespace App\Model;

use App\Model\Department;
use App\Model\Professional_title;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    public function department_name()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
    
    public function professional_title_name()
    {
        return $this->hasOne(Professional_title::class, 'id', 'professional_title_id');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
