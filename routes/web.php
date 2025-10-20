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
        // ��∪極蝞∠��
        Route::resource('staff', 'StaffController');

        // 甈����閮剖��
        Route::resource('access', 'AccessController');
        Route::post('access/update_orderby','AccessController@update_orderby')->name('access.update.orderby');
    });

    Route::middleware('can.settings')->group(function() {

        // ��券��蝞∠��
        Route::resource('department', 'DepartmentController');
        Route::post('department/update_orderby','DepartmentController@update_orderby')->name('department.update.orderby');

        // ��瑞迂蝞∠��
        Route::resource('professional_title', 'Professional_titleController');
        Route::post('professional_title/update_orderby','Professional_titleController@update_orderby')->name('professional_title.update.orderby');

        // 靘�������
        Route::resource('supplier', 'SupplierController');
        Route::post('supplier/search','SupplierController@index')->name('supplier.search');

        // ���撌亙�����
        Route::resource('manufacturer', 'ManufacturerController');
        Route::post('manufacturer/search','ManufacturerController@index')->name('manufacturer.search');

        // ���撌交�孵��
        Route::resource('process_function', 'Process_functionController');
        Route::post('process_function/update_orderby','Process_functionController@update_orderby')->name('process_function.update.orderby');

        // 摰Ｘ�嗥恣���
        Route::resource('customer', 'CustomerController');
        Route::post('customer/search', 'CustomerController@index')->name('customer.search');

        // ��拇�����憿�
        Route::resource('material_category', 'Material_categoryController');
        Route::post('material_category/update_orderby', 'Material_categoryController@update_orderby')->name('material_category.update.orderby');

        // ��桐��
        Route::resource('material_unit', 'Material_unitController');
        Route::post('material_unit/update_orderby', 'Material_unitController@update_orderby')->name('material_unit.update.orderby');

        // ��拇��蝞∠��
        Route::get('material/lastFullCode', 'MaterialController@lastFullCode');
        Route::get('material/lastFullCode/{material_categories_code}', 'MaterialController@lastFullCode');
        Route::resource('material', 'MaterialController');
        Route::post('material/search','MaterialController@index')->name('material.search');
        Route::post('material_file/delete/{file}/{material}/{id}','MaterialController@delete_file');
        Route::post('material/getall', 'MaterialController@getall');

        // ��拇��璅∠��
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

    // ��唾����箏澈
    Route::resource('apply_out_stock', 'ApplyForOutStockController');
    Route::post('apply_out_stock/search','ApplyForOutStockController@search')->name('apply_out_stock.search');
    Route::post('apply_file/delete/{file}/{apply}/{id}','ApplyForOutStockController@delete_file');
    // 摰Ｘ�園�豢��
    Route::get('selectCustomer','SelectController@selectCustomer')->name('selectCustomer');
    Route::get('createCustomer','SelectController@create_customer')->name('createCustomer');
    Route::post('storeCustomer','SelectController@store_customer')->name('storeCustomer');
    Route::post('selectCustomer/search','SelectController@search_customer')->name('selectCustomer.search');

    // ��瑁疏�����拇����豢��
    Route::post('select_material_sale/addRow','SaleController@addRow')->name('select_material_sale.addRow');

    // ��瑁疏�����拇��璅∠����豢��
    Route::post('select_material_module_sale/addRow','SaleController@addModule')->name('select_material_module_sale.addModule');


    Route::post('select_material_inventory/addRow','SelectController@addRow_inventory')->name('select_material_inventory.addRow');
    Route::get('selectMaterial_inventory','SelectController@selectMaterial_inventory')->name('selectMaterial_inventory');
    Route::post('selectMaterial_inventory/search','SelectController@search_material_inventory')->name('selectMaterial_inventory.search');

    Route::post('select_material_module_inventory/addRow','SelectController@addModule_inventory')->name('select_material_module_inventory.addModule');
    Route::get('selectMaterial_module_inventory','SelectController@selectMaterial_module_inventory')->name('selectMaterial_module_inventory');
    Route::post('selectMaterial_module_inventory/search','SelectController@search_material_module_inventory')->name('selectMaterial_module_inventory.search');

    // ��唾����箏澈�����拇����豢��
    Route::post('select_material_apply_out/addRow','SelectController@addRow_apply_out')->name('select_material_apply_out.addRow');
    Route::post('select_material_module_apply_out/addRow','SelectController@addModule_apply_out')->name('select_material_module_apply_out.addModule');



    // ��箏澈
    Route::resource('out_stock', 'OutStockController');
    // ��箏澈�����拇����豢��
    Route::post('select_material_out_stock/addRow','SelectController@addRow_out_stock')->name('select_material_out_stock.addRow');
    // ��箏澈�����拇��璅∠����豢��
    Route::post('select_material_module_out_stock/addRow','SelectController@addModule_out_stock')->name('select_material_module_out_stock.addModule');


    Route::post('select_material_module_purchase/addRow','SelectController@addModule_purchase')->name('select_material_module_purchase.addModule');

    // ������
    Route::resource('account_receivable', 'Account_receivableController');
    Route::post('account_receivable/search','Account_receivableController@search')->name('account_receivable.search');

    // ���鞎�
    Route::resource('s_sales_return', 'Sales_returnController');
    Route::post('s_sales_return/search','Sales_returnController@search')->name('s_sales_return.search');
    Route::post('s_sales_return/search_return','Sales_returnController@search_return')->name('s_sales_return.search_return');
    Route::post('s_sales_return_file/delete/{file}/{sale}/{id}','Sales_returnController@delete_file');

    // ���鞎�
    Route::resource('s_exchange', 'ExchangeController');
    Route::post('s_exchange/search','ExchangeController@search')->name('s_exchange.search');
    Route::post('s_exchange/search_exchange','ExchangeController@search_exchange')->name('s_exchange.search_exchange');
    Route::post('s_exchange_file/delete/{file}/{s_exchange}/{id}','ExchangeController@delete_file');

    // ���鞎冽�輯疏
    Route::resource('picking', 'PickingController');
    // ��嗆狡閮����
    Route::resource('receivable_record', 'Receivable_recordController');
    //�����研����拇膜隡啁��
    Route::resource('prime_cost', 'Prime_costController');
    Route::post('prime_cost/search','Prime_costController@search')->name('prime_cost.search');
});

Route::middleware('admin.login')->middleware('can.stock')->namespace('Stock')->prefix('stock')->group(function() {
    // ��日��
    Route::get('inventory/quick_fix/{inventoryID}/{id}', 'InventoryController@quick_fix');
    Route::resource('inventory', 'InventoryController');
    Route::post('inventory/search', 'InventoryController@search')->name('inventory.search');
    Route::get('inventory/edit_list/{id}', 'InventoryController@edit_list');
    Route::get('inventory/show_list/{id}', 'InventoryController@show_list');

    // ��亙�箏澈
    Route::get('stock/search/{way}/{type}/{year}/{month}', 'StockController@index')->name('stock.search')
        ->where('way', '[0-9]+')->where('type', '[0-9]+')->where('year', '[0-9]+')->where('month', '[0-9]+');
    Route::resource('stock', 'StockController');

    Route::resource('inventory_list', 'Inventory_listController');

    Route::resource('adjustment', 'AdjustmentController');
    Route::post('adjustment/search','AdjustmentController@search')->name('adjustment.search');

    // 隤踵��
    Route::resource('transfer_inventory', 'Transfer_inventoryController');
    Route::post('transfer_inventory/search','Transfer_inventoryController@search')->name('transfer_inventory.search');

    // ��券�����餈質馱
    Route::resource('on_order_follow', 'On_order_followController');
    Route::post('on_order_follow/search','On_order_followController@search')->name('on_order_follow.search');
    // �����������脣漲餈質馱
    Route::resource('semi_finished_schedule', 'Semi_finished_scheduleController');
    Route::post('semi_finished_schedule/search','Semi_finished_scheduleController@search')->name('semi_finished_schedule.search');
    Route::resource('processing_list', 'Processing_listController');
    // 撱������豢��
    Route::get('selectManufacturer','Semi_finished_scheduleController@selectManufacturer')->name('selectManufacturer');
    Route::get('createManufacturer','Semi_finished_scheduleController@create_manufacturer')->name('createManufacturer');
    Route::post('storeManufacturer','Semi_finished_scheduleController@store_manufacturer')->name('storeManufacturer');
    Route::post('selectManufacturer/search','Semi_finished_scheduleController@search_manufacturer')->name('selectManufacturer.search');

    // Route::get('semi_finished_schedule/edit_list/{id}','Semi_finished_scheduleController@edit_list');
    Route::get('semi_finished_schedule/show_list/{id}','Semi_finished_scheduleController@show_list');
    Route::post('semi_finished_schedule/processing/addRow','Semi_finished_scheduleController@addRow_processing')->name('processing.addRow');

    // 擗����������
    Route::resource('residual_material_processing', 'Residual_material_processingController');
    Route::post('residual_material_processing/search','Residual_material_processingController@search')->name('residual_material_processing.search');

});

/*** ��梯” ***/
Route::middleware('admin.login')->prefix('print')->group(
    function () {
        // ��∟頃蝮賢�梯”
        // Route::get('buy', 'PrintController@buy')->name('print.buy');
        // Route::post('buy', 'PrintController@buy')->name('print.buy');

        // ��∟頃蝮賢�梯”
        Route::get('in', 'PrintController@in')->name('print.in');
        Route::post('in', 'PrintController@in')->name('print.in.post');

        // ��瑁疏蝮賢�梯”
        Route::get('out', 'PrintController@out')->name('print.out');
        Route::post('out', 'PrintController@out')->name('print.out.post');

        // ��∟頃��芯��甈曄蜇��梯”
        Route::get('in_unpay', 'PrintController@in_unpay')->name('print.in_unpay');
        Route::post('in_unpay', 'PrintController@in_unpay')->name('print.in_unpay.post');

        // ��瑁疏��芣�嗆狡蝮賢�梯”
        Route::get('out_unpay', 'PrintController@out_unpay')->name('print.out_unpay');
        Route::post('out_unpay', 'PrintController@out_unpay')->name('print.out_unpay.post');

        // ��∟頃��桀�梯”
        Route::get('in_detail/{id}', 'PrintController@in_detail');
        Route::get('in_detail_excel/{id}', 'PrintController@in_detail_excel');
        Route::post('in_detail', 'PrintController@in_detail');

        // ��∟頃��桀�梯” (憭�撘�)
        Route::get('in_details/{ids}', 'PrintController@in_details');
        Route::post('in_details', 'PrintController@in_details');

        // ��瑁疏��桀�梯”
        Route::get('out_detail/{id}', 'PrintController@out_detail');
        Route::post('out_detail', 'PrintController@out_detail');

        // ��瑁疏��桀�梯” (憭�撘�)
        Route::get('out_details/{ids}', 'PrintController@out_details');
        Route::post('out_details', 'PrintController@out_details');

        // ��拇��璅∠��
        Route::get('material_module/{id}', 'PrintController@material_module');
        Route::get('material_module_excel/{id}', 'PrintController@material_module_excel');
    }
);

/*** ��豢����� ***/
Route::middleware('admin.login')->prefix('selector')->group(
    function () {
        // ��拇����豢�����
        Route::get('material/{idx}', 'SelectorController@material');
        Route::get('material/{idx}/{code}', 'SelectorController@material');

        // ��拇��璅∠����豢�����
        Route::get('material_module/{idx}', 'SelectorController@material_module');
        Route::get('material_module/', 'SelectorController@material_module');

        // 摰Ｘ�園�豢�����
        Route::get('customer', 'SelectorController@customer');
        Route::get('customer/{category}', 'SelectorController@customer');

        // 靘���������豢�����
        Route::get('supplier', 'SelectorController@supplier');
        Route::get('supplier/{category}', 'SelectorController@supplier');

        // ���撌亙�������豢�����
        Route::get('manufacturer', 'SelectorController@manufacturer');
        Route::get('manufacturer/{category}', 'SelectorController@manufacturer');

        // ��寡����豢�����
        Route::get('lot', 'SelectorController@lot');

        // ��亙澈蝝����
        Route::get('in_stock_records/{id}', 'SelectorController@in_stock_records');

        // ��箏澈蝝����
        Route::get('out_stock_records/{id}', 'SelectorController@out_stock_records');

        // ��拇��摨怠��蝝����
        Route::get('material_stock_records/{id}', 'SelectorController@material_stock_records');
    }
);

/*** ��寡��蝞∠�� ***/
Route::middleware('admin.login')->middleware('can.settings')->namespace('Settings')->prefix('settings')->group(
    function () {
        Route::resource('lot', 'LotController');
    }
);

// ��脰疏蝞∠��
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
        Route::get('notify', 'InController@notify')->name('in.notify');
        Route::get('notify/{id}', 'InController@notifyView')->name('in.notifyView');
    }
);

// ��瑁疏蝞∠��
Route::middleware('admin.login')->middleware('can.shopping')->namespace('Shopping')->prefix('shopping')->group(
    function () {
        Route::get('out/search/{status}/{pay_status}', 'OutController@index')
            ->where('status', '[0-9]+')
            ->where('pay_status', '[0-9]+')
            ->name('out.search');
        Route::resource('out', 'OutController');
        Route::post('out/{out}/cancel', 'OutController@cancel')->name('out.cancel');
        Route::get('out/{out}/notify', 'OutController@notify')->name('out.notify');
        Route::get('out/{out}/view', 'OutController@edit')->name('out.view');
    }
);

// ��亙澈蝞∠��
// Route::middleware('admin.login')->namespace('Stock')->prefix('purchase')->group(
//     function () {
//         Route::get('stock/search/{type}', 'StockController@index')
//             ->where('type', '[0-9]+');
//         Route::resource('stock', 'StockController');
//     }
// );

/*** 摨怠����日�� ***/
Route::middleware('admin.login')->middleware('can.stock')->namespace('Stock')->prefix('stock')->group(
    function () {
        // ��日��
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

        // ���������
        Route::get('process', 'ProcessController@index')->name('process.index');
    }
);
