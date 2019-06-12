<?php

namespace App\Model;

class Sidebar
{
    static public function settings()
    {
        $data =[];

        $data[] = [
            'route' => 'staff.index',
            'request' => 'settings/staff*',
            'title' => '員工資料'
        ];

        $data[] = [
            'route' => 'department.index',
            'request' => 'settings/department*',
            'title' => '部門設定'
        ];

        $data[] = [
            'route' => 'professional_title.index',
            'request' => 'settings/professional_title*',
            'title' => '職稱設定'
        ];

        return $data;
    }
}
