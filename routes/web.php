<?php

Route::get('/', 'LoginController@index')->name('home');
Route::post('login', 'LoginController@login')->name('login');
Route::get('code', 'LoginController@code')->name('code');

Route::get('dashboard', 'DashboardController@index')->name('dashboard');
Route::get('barcode','BarcodeController@makeBarcode')->name('barcode');
Route::get('barcode/pdf','BarcodeController@makeBarcode_pdf');

Route::get('barcode_PDF/{id}','PDFController@barcode_pdf');

Route::get('logout','LoginController@logout')->name('logout');

Route::middleware('admin.login')->namespace('Settings')->prefix('settings')->group(function(){

    Route::middleware('can.admin')->group(function() {
        // 員工管理
        Route::resource('staff', 'StaffController');

        // 權限設定
        Route::resource('access', 'AccessController');
        Route::post('access/update_orderby','AccessController@update_orderby')->name('access.update.orderby');
    });

    Route::middleware('can.settings')->group(function() {

        // 部門管理
        Route::resource('department', 'DepartmentController');
        Route::post('department/update_orderby','DepartmentController@update_orderby')->name('department.update.orderby');

        // 職稱管理
        Route::resource('professional_title', 'Professional_titleController');
        Route::post('professional_title/update_orderby','Professional_titleController@update_orderby')->name('professional_title.update.orderby');

        // 供應商
        Route::resource('supplier', 'SupplierController');
        Route::post('supplier/search','SupplierController@index')->name('supplier.search');

        // 加工廠商
        Route::resource('manufacturer', 'ManufacturerController');
        Route::post('manufacturer/search','ManufacturerController@index')->name('manufacturer.search');

        // 加工方式
        Route::resource('process_function', 'Process_functionController');
        Route::post('process_function/update_orderby','Process_functionController@update_orderby')->name('process_function.update.orderby');

        // 客戶管理
        Route::resource('customer', 'CustomerController');
        Route::post('customer/search', 'CustomerController@index')->name('customer.search');

        // 物料分類
        Route::resource('material_category', 'Material_categoryController');
        Route::post('material_category/update_orderby', 'Material_categoryController@update_orderby')->name('material_category.update.orderby');

        // 單位
        Route::resource('material_unit', 'Material_unitController');
        Route::post('material_unit/update_orderby', 'Material_unitController@update_orderby')->name('material_unit.update.orderby');

        // 物料管理
        Route::get('material/lastFullCode', 'MaterialController@lastFullCode');
        Route::get('material/lastFullCode/{material_categories_code}', 'MaterialController@lastFullCode');
        Route::resource('material', 'MaterialController');
        Route::post('material/search','MaterialController@index')->name('material.search');
        Route::post('material_file/delete/{file}/{material}/{id}','MaterialController@delete_file');
        Route::post('material/getall', 'MaterialController@getall');

        // 物料模組
        Route::get('material_module/duplicate/{from}', 'Material_moduleController@duplicate')->name('material_module.duplicate');
        Route::resource('material_module', 'Material_moduleController');
        Route::post('material_module/search','Material_moduleController@search')->name('material_module.search');
        Route::post('material_module_file/delete/{file}/{material}/{id}','Material_moduleController@delete_file');
        Route::post('material_module/getall', 'Material_moduleController@getall');

    });
});

Route::middleware('admin.login')->middleware('can.shopping')->namespace('Shopping')->prefix('shopping')->group(function(){
    Route::resource('sale', 'SaleController');
    Route::post('sale/search','SaleController@search')->name('sale.search');
    Route::post('sale_file/delete/{file}/{sale}/{id}','SaleController@delete_file');

    // 申請出庫
    Route::resource('apply_out_stock', 'ApplyForOutStockController');
    Route::post('apply_out_stock/search','ApplyForOutStockController@search')->name('apply_out_stock.search');
    Route::post('apply_file/delete/{file}/{apply}/{id}','ApplyForOutStockController@delete_file');
    // 客戶選擇
    Route::get('selectCustomer','SelectController@selectCustomer')->name('selectCustomer');
    Route::get('createCustomer','SelectController@create_customer')->name('createCustomer');
    Route::post('storeCustomer','SelectController@store_customer')->name('storeCustomer');
    Route::post('selectCustomer/search','SelectController@search_customer')->name('selectCustomer.search');

    // 銷貨的物料選擇
    Route::post('select_material_sale/addRow','SaleController@addRow')->name('select_material_sale.addRow');

    // 銷貨的物料模組選擇
    Route::post('select_material_module_sale/addRow','SaleController@addModule')->name('select_material_module_sale.addModule');


    Route::post('select_material_inventory/addRow','SelectController@addRow_inventory')->name('select_material_inventory.addRow');
    Route::get('selectMaterial_inventory','SelectController@selectMaterial_inventory')->name('selectMaterial_inventory');
    Route::post('selectMaterial_inventory/search','SelectController@search_material_inventory')->name('selectMaterial_inventory.search');

    Route::post('select_material_module_inventory/addRow','SelectController@addModule_inventory')->name('select_material_module_inventory.addModule');
    Route::get('selectMaterial_module_inventory','SelectController@selectMaterial_module_inventory')->name('selectMaterial_module_inventory');
    Route::post('selectMaterial_module_inventory/search','SelectController@search_material_module_inventory')->name('selectMaterial_module_inventory.search');

    // 申請出庫的物料選擇
    Route::post('select_material_apply_out/addRow','SelectController@addRow_apply_out')->name('select_material_apply_out.addRow');
    Route::post('select_material_module_apply_out/addRow','SelectController@addModule_apply_out')->name('select_material_module_apply_out.addModule');



    // 出庫
    Route::resource('out_stock', 'OutStockController');
    // 出庫的物料選擇
    Route::post('select_material_out_stock/addRow','SelectController@addRow_out_stock')->name('select_material_out_stock.addRow');
    // 出庫的物料模組選擇
    Route::post('select_material_module_out_stock/addRow','SelectController@addModule_out_stock')->name('select_material_module_out_stock.addModule');


    Route::post('select_material_module_purchase/addRow','SelectController@addModule_purchase')->name('select_material_module_purchase.addModule');

    // 應收
    Route::resource('account_receivable', 'Account_receivableController');
    Route::post('account_receivable/search','Account_receivableController@search')->name('account_receivable.search');

    // 退貨
    Route::resource('s_sales_return', 'Sales_returnController');
    Route::post('s_sales_return/search','Sales_returnController@search')->name('s_sales_return.search');
    Route::post('s_sales_return/search_return','Sales_returnController@search_return')->name('s_sales_return.search_return');
    Route::post('s_sales_return_file/delete/{file}/{sale}/{id}','Sales_returnController@delete_file');

    // 換貨
    Route::resource('s_exchange', 'ExchangeController');
    Route::post('s_exchange/search','ExchangeController@search')->name('s_exchange.search');
    Route::post('s_exchange/search_exchange','ExchangeController@search_exchange')->name('s_exchange.search_exchange');
    Route::post('s_exchange_file/delete/{file}/{s_exchange}/{id}','ExchangeController@delete_file');

    // 集貨撿貨
    Route::resource('picking', 'PickingController');
    // 收款記錄
    Route::resource('receivable_record', 'Receivable_recordController');
    //成本、利潤估算
    Route::resource('prime_cost', 'Prime_costController');
    Route::post('prime_cost/search','Prime_costController@search')->name('prime_cost.search');
});

Route::middleware('admin.login')->middleware('can.stock')->namespace('Stock')->prefix('stock')->group(function() {
    // 盤點
    Route::get('inventory/quick_fix/{inventoryID}/{id}', 'InventoryController@quick_fix');
    Route::resource('inventory', 'InventoryController');
    Route::post('inventory/search', 'InventoryController@search')->name('inventory.search');
    Route::get('inventory/edit_list/{id}', 'InventoryController@edit_list');
    Route::get('inventory/show_list/{id}', 'InventoryController@show_list');

    // 入出庫
    Route::get('stock/search/{way}/{type}/{year}/{month}', 'StockController@index')->name('stock.search')
        ->where('way', '[0-9]+')->where('type', '[0-9]+')->where('year', '[0-9]+')->where('month', '[0-9]+');
    Route::resource('stock', 'StockController');

    Route::resource('inventory_list', 'Inventory_listController');

    Route::resource('adjustment', 'AdjustmentController');
    Route::post('adjustment/search','AdjustmentController@search')->name('adjustment.search');

    // 調撥
    Route::resource('transfer_inventory', 'Transfer_inventoryController');
    Route::post('transfer_inventory/search','Transfer_inventoryController@search')->name('transfer_inventory.search');

    // 在途量追蹤
    Route::resource('on_order_follow', 'On_order_followController');
    Route::post('on_order_follow/search','On_order_followController@search')->name('on_order_follow.search');
    // 半成品進度追蹤
    Route::resource('semi_finished_schedule', 'Semi_finished_scheduleController');
    Route::post('semi_finished_schedule/search','Semi_finished_scheduleController@search')->name('semi_finished_schedule.search');
    Route::resource('processing_list', 'Processing_listController');
    // 廠商選擇
    Route::get('selectManufacturer','Semi_finished_scheduleController@selectManufacturer')->name('selectManufacturer');
    Route::get('createManufacturer','Semi_finished_scheduleController@create_manufacturer')->name('createManufacturer');
    Route::post('storeManufacturer','Semi_finished_scheduleController@store_manufacturer')->name('storeManufacturer');
    Route::post('selectManufacturer/search','Semi_finished_scheduleController@search_manufacturer')->name('selectManufacturer.search');

    // Route::get('semi_finished_schedule/edit_list/{id}','Semi_finished_scheduleController@edit_list');
    Route::get('semi_finished_schedule/show_list/{id}','Semi_finished_scheduleController@show_list');
    Route::post('semi_finished_schedule/processing/addRow','Semi_finished_scheduleController@addRow_processing')->name('processing.addRow');

    // 餘料處理
    Route::resource('residual_material_processing', 'Residual_material_processingController');
    Route::post('residual_material_processing/search','Residual_material_processingController@search')->name('residual_material_processing.search');

});

/*** 報表 ***/
Route::middleware('admin.login')->prefix('print')->group(
    function () {
        // 採購總報表
        // Route::get('buy', 'PrintController@buy')->name('print.buy');
        // Route::post('buy', 'PrintController@buy')->name('print.buy');

        // 採購總報表
        Route::get('in', 'PrintController@in')->name('print.in');
        Route::post('in', 'PrintController@in')->name('print.in');

        // 銷貨總報表
        Route::get('out', 'PrintController@out')->name('print.out');
        Route::post('out', 'PrintController@out')->name('print.out');

        // 採購未付款總報表
        Route::get('in_unpay', 'PrintController@in_unpay')->name('print.in_unpay');
        Route::post('in_unpay', 'PrintController@in_unpay')->name('print.in_unpay');

        // 銷貨未收款總報表
        Route::get('out_unpay', 'PrintController@out_unpay')->name('print.out_unpay');
        Route::post('out_unpay', 'PrintController@out_unpay')->name('print.out_unpay');

        // 採購單報表
        Route::get('in_detail/{id}', 'PrintController@in_detail');
        Route::get('in_detail_excel/{id}', 'PrintController@in_detail_excel');
        Route::post('in_detail', 'PrintController@in_detail');

        // 採購單報表 (多張)
        Route::get('in_details/{ids}', 'PrintController@in_details');
        Route::post('in_details', 'PrintController@in_details');

        // 銷貨單報表
        Route::get('out_detail/{id}', 'PrintController@out_detail');
        Route::post('out_detail', 'PrintController@out_detail');

        // 銷貨單報表 (多張)
        Route::get('out_details/{ids}', 'PrintController@out_details');
        Route::post('out_details', 'PrintController@out_details');

        // 物料模組
        Route::get('material_module/{id}', 'PrintController@material_module');
        Route::get('material_module_excel/{id}', 'PrintController@material_module_excel');
    }
);

/*** 選擇器 ***/
Route::middleware('admin.login')->prefix('selector')->group(
    function () {
        // 物料選擇器
        Route::get('material/{idx}', 'SelectorController@material');
        Route::get('material/{idx}/{code}', 'SelectorController@material');

        // 物料模組選擇器
        Route::get('material_module/{idx}', 'SelectorController@material_module');
        Route::get('material_module/', 'SelectorController@material_module');

        // 客戶選擇器
        Route::get('customer', 'SelectorController@customer');
        Route::get('customer/{category}', 'SelectorController@customer');

        // 供應商選擇器
        Route::get('supplier', 'SelectorController@supplier');
        Route::get('supplier/{category}', 'SelectorController@supplier');

        // 加工廠商選擇器
        Route::get('manufacturer', 'SelectorController@manufacturer');
        Route::get('manufacturer/{category}', 'SelectorController@manufacturer');

        // 批號選擇器
        Route::get('lot', 'SelectorController@lot');

        // 入庫紀錄
        Route::get('in_stock_records/{id}', 'SelectorController@in_stock_records');

        // 出庫紀錄
        Route::get('out_stock_records/{id}', 'SelectorController@out_stock_records');

        // 物料庫存紀錄
        Route::get('material_stock_records/{id}', 'SelectorController@material_stock_records');
    }
);

/*** 批號管理 ***/
Route::middleware('admin.login')->middleware('can.settings')->namespace('Settings')->prefix('settings')->group(
    function () {
        Route::resource('lot', 'LotController');
    }
);

// 進貨管理
Route::middleware('admin.login')->middleware('can.purchase')->namespace('Purchase')->prefix('purchase')->group(
    function () {
        Route::get('on', 'OnController@index')->name('on.index');
        Route::get('ion', 'OnController@in')->name('ion.index');

        Route::get('in/search/{status}/{pay_status}', 'InController@index')
            ->where('status', '[0-9]+')
            ->where('pay_status', '[0-9]+')
            ->name('in.search');
        Route::resource('in', 'InController');

        Route::get('aloneIn/{in_id}/{material_id}', 'InController@aloneIn');
        Route::post('aloneIn', 'InController@aloneInStore');
    }
);

// 銷貨管理
Route::middleware('admin.login')->middleware('can.shopping')->namespace('Shopping')->prefix('shopping')->group(
    function () {
        Route::get('out/search/{status}/{pay_status}', 'OutController@index')
            ->where('status', '[0-9]+')
            ->where('pay_status', '[0-9]+')
            ->name('out.search');
        Route::resource('out', 'OutController');
        Route::post('out/{out}/cancel', 'OutController@cancel')->name('out.cancel');
    }
);

// 入庫管理
// Route::middleware('admin.login')->namespace('Stock')->prefix('purchase')->group(
//     function () {
//         Route::get('stock/search/{type}', 'StockController@index')
//             ->where('type', '[0-9]+');
//         Route::resource('stock', 'StockController');
//     }
// );

/*** 庫存盤點 ***/
Route::middleware('admin.login')->middleware('can.stock')->namespace('Stock')->prefix('stock')->group(
    function () {
        // 盤點
        Route::get('inventory/{id}/view', 'InventoryController@view')
            ->where('id', '[0-9]+')->name('inventory.view');
        Route::get('inventory/{id}/check', 'InventoryController@check')
            ->where('id', '[0-9]+')->name('inventory.check');
        Route::get('inventory/{id}/quickFix', 'InventoryController@quickFix')
            ->where('id', '[0-9]+')->name('inventory.quickFix');
        Route::get('inventory/{id}/fix', 'InventoryController@fix')
            ->where('id', '[0-9]+')->name('inventory.fix');
        Route::post('inventory/fixSave', 'InventoryController@fixSave')->name('inventory.fixSave');
        Route::post('inventory/record', 'InventoryController@record')->name('inventory.record');

        Route::get('inventory/search/{status}', 'InventoryController@index')->name('inventory.search');
        Route::resource('inventory', 'InventoryController');

        // 半成品
        Route::get('process', 'ProcessController@index')->name('process.index');
    }
);
