<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Model\Access_detail;

class Access extends Model
{
    use HasFactory;

    static public function groups()
    {
        $groups = [];
        $groups['admin'] = '帳號權限';
        $groups['settings'] = '基本資料';
        $groups['purchase'] = '採購進貨';
        $groups['shopping'] = '銷貨出貨';
        $groups['stock'] = '庫存盤點';

        return $groups;
    }

    static public function modes()
    {
        $modes = [];
        $modes['none'] = '無權限';
        $modes['view'] = '檢視';
        $modes['admin'] = '管理';

        return $modes;
    }

    static public function templates()
    {
        $templates = [];
        $templates['admin'] = '最高管理者';
        $templates['finance'] = '財務部門';
        $templates['purchase'] = '採購部門';
        $templates['storage'] = '倉庫部門';
        $templates['design'] = '設計部門/其他';

        return $templates;
    }

    public function getAccess($group)
    {
        $access_detail = Access_detail
            ::where('access_id', $this->id)
            ->where('group', $group)
            ->first();
        return $access_detail ? $access_detail->mode : 'none';
    }

    static public function max_orderby() {
        $max_orderby = Access::orderBy('orderby', 'DESC')->first();

        return $max_orderby ? $max_orderby->orderby : 0;
    }
}
