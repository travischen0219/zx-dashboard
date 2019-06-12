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
            'route' => 'supplier.index',
            'request' => 'settings/professional_title*',
            'title' => '職稱設定'
        ];

        $data[] = [
            'route' => 'department.index',
            'request' => 'settings/supplier*',
            'title' => '供應商'
        ];

        $data[] = [
            'route' => 'manufacturer.index',
            'request' => 'settings/manufacturer*',
            'title' => '廠商資料'
        ];

        $data[] = [
            'route' => 'process_function.index',
            'request' => 'settings/process_function*',
            'title' => '加工方式'
        ];

        $data[] = [
            'route' => 'materials.index',
            'request' => 'settings/materials*',
            'title' => '物料管理'
        ];

        $data[] = [
            'route' => 'material_category.index',
            'request' => 'settings/material_category*',
            'title' => '物料分類設定'
        ];

        $data[] = [
            'route' => 'material_unit.index',
            'request' => 'settings/material_unit*',
            'title' => '單位設定'
        ];

        $data[] = [
            'route' => 'material_module.index',
            'request' => 'settings/material_module*',
            'title' => '物料模組'
        ];

        $data[] = [
            'route' => 'customers.index',
            'request' => 'settings/customers*',
            'title' => '客戶資料'
        ];

        $data[] = [
            'route' => 'warehouses.index',
            'request' => 'settings/warehouses*',
            'title' => '倉儲資料'
        ];

        $data[] = [
            'route' => 'warehouse_category.index',
            'request' => 'settings/warehouse_category*',
            'title' => '倉儲分類設定'
        ];

        return $data;
    }
}
