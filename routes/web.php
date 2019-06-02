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
    Route::resource('staff', 'StaffController');

    Route::resource('department', 'DepartmentController');
    Route::post('department/update_orderby','DepartmentController@update_orderby')->name('department.update.orderby');

    Route::resource('professional_title', 'Professional_titleController');
    Route::post('professional_title/update_orderby','Professional_titleController@update_orderby')->name('professional_title.update.orderby');
    // 供應商
    Route::resource('supplier', 'SupplierController');
    Route::post('supplier/search','SupplierController@search')->name('supplier.search');
    // 廠商
    Route::resource('manufacturer', 'ManufacturerController');
    Route::post('manufacturer/search','ManufacturerController@search')->name('manufacturer.search');
    // 加工方式
    Route::resource('process_function', 'Process_functionController');
    Route::post('process_function/update_orderby','Process_functionController@update_orderby')->name('process_function.update.orderby');


    Route::resource('customers', 'CustomerController');
    Route::post('customers/search','CustomerController@search')->name('customers.search');

    Route::resource('material_category', 'Material_categoryController');
    Route::post('material_category/update_orderby','Material_categoryController@update_orderby')->name('material_category.update.orderby');

    Route::resource('material_unit', 'Material_unitController');
    Route::post('material_unit/update_orderby','Material_unitController@update_orderby')->name('material_unit.update.orderby');

    Route::resource('materials', 'MaterialController');
    Route::post('materials/search','MaterialController@search')->name('materials.search');
    Route::post('material_file/delete/{file}/{material}/{id}','MaterialController@delete_file');


    Route::resource('warehouses', 'WarehouseController');
    Route::post('warehouses/search','WarehouseController@search')->name('warehouses.search');
    Route::get('selectWarehouse','SelectController@selectWarehouse')->name('selectWarehouse');
    Route::post('selectWarehouse/search','SelectController@search_warehouse')->name('selectWarehouse.search');
    Route::post('warehouse_file/delete/{file}/{material}/{id}','WarehouseController@delete_file');

    // 出庫 倉儲選擇
    Route::get('selectWarehouse_stock/{id}','SelectController@selectWarehouse_stock');
    Route::post('selectWarehouse_stock/search','SelectController@search_warehouse_stock')->name('selectWarehouse_stock.search');
    // 差異處理 調撥 倉儲選擇
    Route::get('selectWarehouse_byMaterial/{id}','SelectController@selectWarehouse_byMaterial');
    // 轉入庫 選物料
    Route::get('selectMaterial_byId/{id}','SelectController@selectMaterial_byId');
    // 調撥 新倉儲選擇
    Route::get('selectNewWarehouse_stock/{id}','SelectController@selectNewWarehouse_stock');
    Route::post('selectNewWarehouse_stock/search','SelectController@search_new_warehouse_stock')->name('selectNewWarehouse_stock.search');

    Route::resource('warehouse_category', 'Warehouse_categoryController');
    Route::post('warehouse_category/update_orderby','Warehouse_categoryController@update_orderby')->name('warehouse_category.update.orderby');
    // 物料管理 秀出庫存列表
    Route::get('show_stock','SelectController@show_stock')->name('show_stock');
    Route::get('show_stock/{id}','SelectController@show_stock')->name('show_stock.detail');

    Route::resource('gallery', 'GalleryController');
    Route::get('file_download/{id}', 'GalleryController@file_download');
    Route::post('gallery/search','GalleryController@search')->name('gallery.search');

    // 物料模組
    Route::resource('material_module', 'Material_moduleController');
    Route::post('material_module/search','Material_moduleController@search')->name('material_module.search');
    Route::post('material_module_file/delete/{file}/{material}/{id}','Material_moduleController@delete_file');



});

Route::middleware('admin.login')->namespace('Purchase')->prefix('purchase')->group(function(){
    Route::resource('inquiry', 'InquiryController');
    Route::post('inquiry/search','InquiryController@search')->name('inquiry.search');

    Route::get('selectSupplier','SelectController@selectSupplier')->name('selectSupplier');
    Route::get('createSupplier','SelectController@create_supplier')->name('createSupplier');
    Route::post('storeSupplier','SelectController@store_supplier')->name('storeSupplier');
    Route::post('selectSupplier/search','SelectController@search_supplier')->name('selectSupplier.search');


    Route::post('selectMaterial/addRow','SelectController@addRow')->name('selectMaterial.addRow');
    Route::post('selectMaterial_module/addRow','SelectController@addRow_module')->name('selectMaterial_module.addRow');
    Route::get('selectMaterial','SelectController@selectMaterial')->name('selectMaterial');
    Route::get('createMaterial','SelectController@create_Material')->name('createMaterial');
    Route::post('storeMaterial','SelectController@store_Material')->name('storeMaterial');
    Route::post('selectMaterial/search','SelectController@search_material')->name('selectMaterial.search');

    // 詢價 新增物料列
    Route::post('selectMaterial_inquery/addRow','InquiryController@addRow')->name('selectMaterial_inquery.addRow');
    // 詢價 新增物料模組
    Route::post('selectMaterialModule_inquery/addRow','InquiryController@addModule')->name('selectMaterialModule_inquery.addModule');

    // 採購 新增物料列
    Route::post('selectMaterial_buy/addRow','BuyController@addRow')->name('selectMaterial_buy.addRow');
    // 採購 新增物料模組
    Route::post('selectMaterialModule_buy/addRow','BuyController@addModule')->name('selectMaterialModule_buy.addModule');


    Route::get('selectMaterial_stock','SelectController@selectMaterial_stock')->name('selectMaterial_stock');
    Route::post('selectMaterial_stock/search','SelectController@search_material_stock')->name('selectMaterial_stock.search');

    Route::resource('buy', 'BuyController');
    Route::post('buy/search','BuyController@search')->name('buy.search');

    // 採購轉入庫中
    Route::resource('ibuy_to_stock', 'Buy_to_stockController');
    Route::post('ibuy_to_stock/search','Buy_to_stockController@search')->name('ibuy_to_stock.search');
    Route::post('select_material_toStock/addRow','SelectController@addRow_toStock')->name('select_material_toStock.addRow');

    Route::resource('stock', 'StockController');
    Route::post('stock/search','StockController@search')->name('stock.search');

    Route::post('select_material_stock/addRow','SelectController@addRow_stock')->name('select_material_stock.addRow');
    // 差異處理 新增物料列
    Route::post('select_material_adjustment/addRow','SelectController@addRow_adjustment')->name('select_material_adjustment.addRow');
    // 調撥 新增物料列
    Route::post('select_material_transfer_inventory/addRow','SelectController@addRow_transfer')->name('select_material_transfer_inventory.addRow');

    Route::post('get_warehouse_stock','SelectController@get_warehouse_stock')->name('get_warehouse_stock');

    Route::get('account_payable/print','Account_payableController@print')->name('account_payable.print');
    Route::resource('account_payable', 'Account_payableController');
    Route::post('account_payable/search','Account_payableController@index')->name('account_payable.search');

    // 付款記錄
    Route::resource('payment_record', 'Payment_recordController');

    // 退貨
    Route::resource('p_sales_return', 'P_sales_returnController');
    Route::post('p_sales_return/search','P_sales_returnController@search')->name('p_sales_return.search');
    Route::post('p_sales_return/search_return','P_sales_returnController@search_return')->name('p_sales_return.search_return');
    // Route::post('sales_return/delete/{file}/{sale}/{id}','SaleController@delete_file');

    // 換貨
    Route::resource('p_exchange', 'P_exchangeController');
    Route::post('p_exchange/search','P_exchangeController@search')->name('p_exchange.search');
    Route::post('p_exchange/search_exchange','P_exchangeController@search_exchange')->name('p_exchange.search_exchange');


    // 月報表
    Route::get('monthly_report/print', 'Monthly_reportController@print')->name('monthly_report.print');
    Route::post('monthly_report/print', 'Monthly_reportController@print')->name('monthly_report.print');
    Route::resource('monthly_report', 'Monthly_reportController');
    // 年報表
    Route::resource('annual_report', 'Annual_reportController');

});

Route::middleware('admin.login')->namespace('Shopping')->prefix('shopping')->group(function(){
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

Route::middleware('admin.login')->namespace('Stock')->prefix('stock')->group(function(){

    Route::get('inventory/quick_fix/{inventoryID}/{id}','InventoryController@quick_fix');
    Route::resource('inventory', 'InventoryController');
    Route::post('inventory/search','InventoryController@search')->name('inventory.search');
    Route::get('inventory/edit_list/{id}','InventoryController@edit_list');
    Route::get('inventory/show_list/{id}','InventoryController@show_list');

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

Route::middleware('admin.login')->prefix('print')->group(function(){
    // 採購總報表
    Route::get('buy','PrintController@buy');
    Route::post('buy','PrintController@buy');

    // 採購單報表
    Route::get('buy_detail/{id}','PrintController@buy_detail');
    Route::post('buy_detail','PrintController@buy_detail');

    // 採購單報表 (多張)
    Route::get('buy_details/{ids}','PrintController@buy_details');
    Route::post('buy_details','PrintController@buy_details');

    // 物料模組
    Route::get('material_module/{id}','PrintController@material_module');
});

Route::middleware('admin.login')->prefix('selector')->group(function(){
    // 物料選擇器
    Route::get('material/{idx}','SelectorController@material');
    Route::get('material/{idx}/{code}','SelectorController@material');
});
