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
            'title' => '公司資料'
        ];

        // $data[] = [
        //     'route' => 'staff.index',
        //     'request' => 'settings/staff*',
        //     'title' => '員工資料'
        // ];

        // $data[] = [
        //     'route' => 'department.index',
        //     'request' => 'settings/department*',
        //     'title' => '部門設定'
        // ];

        // $data[] = [
        //     'route' => 'professional_title.index',
        //     'request' => 'settings/professional_title*',
        //     'title' => '職稱設定'
        // ];

        $data[] = [
            'route' => 'supplier.index',
            'request' => 'settings/supplier*',
            'title' => '供應商'
        ];

        $data[] = [
            'route' => 'manufacturer.index',
            'request' => 'settings/manufacturer*',
            'title' => '加工廠商'
        ];

        $data[] = [
            'route' => 'process_function.index',
            'request' => 'settings/process_function*',
            'title' => '加工方式'
        ];

        $data[] = [
            'route' => 'material.index',
            'request' => 'settings/material*',
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
            'route' => 'lot.index',
            'request' => 'settings/lot*',
            'title' => '批號管理'
        ];

        // $data[] = [
        //     'route' => 'warehouses.index',
        //     'request' => 'settings/warehouses*',
        //     'title' => '倉儲資料'
        // ];

        // $data[] = [
        //     'route' => 'warehouse_category.index',
        //     'request' => 'settings/warehouse_category*',
        //     'title' => '倉儲分類設定'
        // ];

        return $data;
    }

    static public function purchases()
    {
        $data =[];

        $data[] = [
            'route' => 'in.index',
            'request' => 'purchase/in*',
            'title' => '採購'
        ];

        $data[] = [
            'route' => 'on.index',
            'request' => 'purchase/on*',
            'title' => '在途量追蹤 (依照物料)'
        ];

        $data[] = [
            'route' => 'ion.index',
            'request' => 'purchase/ion*',
            'title' => '在途量追蹤 (依照採購單)'
        ];

        $data[] = [
            'route' => 'stock.index',
            'request' => 'purchase/stock*',
            'title' => '入庫'
        ];

        // $data[] = [
        //     'route' => 'account_payable.index',
        //     'request' => 'purchase/account_payable*',
        //     'title' => '應付帳款管理'
        // ];

        // $data[] = [
        //     'route' => 'payment_record.index',
        //     'request' => 'purchase/payment_record*',
        //     'title' => '付款記錄'
        // ];

        // $data[] = [
        //     'route' => 'p_sales_return.index',
        //     'request' => 'purchase/p_sales_return*',
        //     'title' => '採購退貨'
        // ];

        // $data[] = [
        //     'route' => 'p_exchange.index',
        //     'request' => 'purchase/p_exchange*',
        //     'title' => '採購換貨'
        // ];

        $data[] = [
            'route' => 'print.in',
            'request' => '/print/in*',
            'title' => '採購報表',
            'target' => '_blank'
        ];

        $data[] = [
            'route' => 'print.in_unpay',
            'request' => '/print/in_unpay*',
            'title' => '未付款資料',
            'target' => '_blank'
        ];

        return $data;
    }

    static public function shoppings()
    {
        $data =[];

        $data[] = [
            'route' => 'sale.index',
            'request' => 'shopping/sale*',
            'title' => '銷貨'
        ];

        $data[] = [
            'route' => 'apply_out_stock.index',
            'request' => 'shopping/apply_out_stock*',
            'title' => '申請出庫'
        ];

        $data[] = [
            'route' => 'picking.index',
            'request' => 'shopping/picking*',
            'title' => '集貨撿貨'
        ];

        $data[] = [
            'route' => 'out_stock.index',
            'request' => 'shopping/out_stock*',
            'title' => '出庫'
        ];

        $data[] = [
            'route' => 'account_receivable.index',
            'request' => 'shopping/account_receivable*',
            'title' => '應收帳款管理'
        ];

        $data[] = [
            'route' => 'receivable_record.index',
            'request' => 'shopping/receivable_record*',
            'title' => '收款記錄'
        ];

        $data[] = [
            'route' => 's_sales_return.index',
            'request' => 'shopping/s_sales_return*',
            'title' => '銷貨退貨'
        ];

        $data[] = [
            'route' => 's_exchange.index',
            'request' => 'shopping/s_exchange*',
            'title' => '銷貨換貨'
        ];

        $data[] = [
            'route' => 'prime_cost.index',
            'request' => 'shopping/prime_cost*',
            'title' => '成本利潤估算'
        ];

        return $data;
    }

    static public function stocks()
    {
        $data =[];

        $data[] = [
            'route' => 'inventory.index',
            'request' => 'stock/inventory*',
            'title' => '盤點'
        ];

        // $data[] = [
        //     'route' => 'adjustment.index',
        //     'request' => 'stock/adjustment*',
        //     'title' => '誤差處理'
        // ];

        // $data[] = [
        //     'route' => 'transfer_inventory.index',
        //     'request' => 'stock/transfer_inventory*',
        //     'title' => '調撥'
        // ];

        // $data[] = [
        //     'route' => 'on_order_follow.index',
        //     'request' => 'stock/on_order_follow*',
        //     'title' => '在途量追蹤'
        // ];

        $data[] = [
            'route' => 'semi_finished_schedule.index',
            'request' => 'stock/semi_finished_schedule*',
            'title' => '半成品進度追蹤'
        ];

        $data[] = [
            'route' => 'residual_material_processing.index',
            'request' => 'stock/residual_material_processing*',
            'title' => '餘料處理'
        ];

        return $data;
    }
}
