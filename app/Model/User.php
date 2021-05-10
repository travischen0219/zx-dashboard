<?php

namespace App\Model;

use App\Model\Access;
use App\Model\Access_detail;
use App\Model\Department;
use App\Model\Professional_title;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public function department_name()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function access_name()
    {
        return $this->hasOne(Access::class, 'id', 'access_id');
    }

    public function professional_title_name()
    {
        return $this->hasOne(Professional_title::class, 'id', 'professional_title_id');
    }

    static public function canView($group)
    {
        if (session('admin_user') == null) {
            return false;
        }

        $user = User::find(session('admin_user')->id);

        if (!$user) {
            return false;
        }
        $access_id = $user->access_id;
        $access_detail = Access_detail
            ::where('access_id', $access_id)
            ->where('group', $group)
            ->first();
        $mode = $access_detail ? $access_detail->mode : 'none';

        return ($mode == 'view' || $mode == 'admin');
    }

    static public function canAdmin($group)
    {
        $user = User::find(session('admin_user')->id);
        if (!$user) {
            return false;
        }

        $access_id = $user->access_id;
        $access_detail = Access_detail
            ::where('access_id', $access_id)
            ->where('group', $group)
            ->first();
        $mode = $access_detail ? $access_detail->mode : 'none';

        return ($mode == 'admin');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'staff_code',
        'fullname',
        'access_id',
        'tel',
        'mobile',
        'address',
        'email',
        'status',
        'memo',
        'password'
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
