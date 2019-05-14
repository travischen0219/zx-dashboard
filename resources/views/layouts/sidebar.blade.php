<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
        <!-- BEGIN SIDEBAR -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <div class="page-sidebar navbar-collapse collapse">
            <!-- BEGIN SIDEBAR MENU -->
            <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
            <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
            <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
            <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
            <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
            <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
            <ul class="page-sidebar-menu page-header-fixed page-sidebar-menu-hover-submenu" data-keep-expanded="true" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <li class="sidebar-toggler-wrapper hide">
                    <div class="sidebar-toggler">
                        <span></span>
                    </div>
                </li>
                <!-- END SIDEBAR TOGGLER BUTTON -->
                
                <li class="nav-item start 
                    @if(Request::is('dashboard*'))
                        active open
                    @endif
                    ">
                    <a href="{{ route('dashboard') }}" class="nav-link nav-toggle">
                        <i class="icon-home"></i>
                        <span class="title">首頁</span>
                        @if(Request::is('dashboard*'))
                            <span class="selected"></span>
                        @endif
                    </a>
                    
                </li>
                <li class="heading">
                    {{--  <h3 class="uppercase">Features</h3>  --}}
                </li>
                <li class="nav-item 
                    @if(Request::is('settings*'))
                        active open
                    @endif ">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-settings"></i>
                        <span class="title">基本資料</span>
                        <span class="arrow"></span>
                        @if(Request::is('settings*'))
                            <span class="selected"></span>
                        @endif
                    </a>
                    <ul class="sub-menu">

                        <li class="nav-item  
                            @if(Request::is('settings/staff*'))
                                active open
                            @endif">
                            <a href="{{ route('staff.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/staff*'))
                                        style="color:#59deea;"
                                    @endif>員工資料</span>
                                @if(Request::is('settings/staff*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('settings/department*'))
                                active open
                            @endif">
                            <a href="{{ route('department.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/department*'))
                                        style="color:#59deea;"
                                    @endif>部門設定</span>
                                @if(Request::is('settings/department*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('settings/professional_title*'))
                                active open
                            @endif">
                            <a href="{{ route('professional_title.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/professional_title*'))
                                        style="color:#59deea;"
                                    @endif>職稱設定</span>
                                @if(Request::is('settings/professional_title*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        {{-- <li class="nav-item  ">
                            <a href="#" class="nav-link ">
                                <span class="title">角色權限(建構中)</span>

                            </a>
                        </li> --}}

                        <li class="nav-item  
                            @if(Request::is('settings/supplier*'))
                                active open
                            @endif">
                            <a href="{{ route('supplier.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/supplier*'))
                                        style="color:#59deea;"
                                    @endif>供應商</span>
                                @if(Request::is('settings/supplier*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('settings/manufacturer*'))
                                active open
                            @endif">
                            <a href="{{ route('manufacturer.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/manufacturer*'))
                                        style="color:#59deea;"
                                    @endif>廠商資料</span>
                                @if(Request::is('settings/manufacturer*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('settings/process_function*'))
                                active open
                            @endif">
                            <a href="{{ route('process_function.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('settings/process_function*'))
                                        style="color:#59deea;"
                                    @endif>加工方式</span>
                                @if(Request::is('settings/process_function*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('settings/materials*'))
                                active open
                            @endif">
                            <a href="{{ route('materials.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/materials*'))
                                        style="color:#59deea;"
                                    @endif>物料管理</span>
                                @if(Request::is('settings/materials*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('settings/material_category*'))
                                active open
                            @endif">
                            <a href="{{ route('material_category.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/material_category*'))
                                        style="color:#59deea;"
                                    @endif>物料分類設定</span>
                                @if(Request::is('settings/material_category*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item  
                            @if(Request::is('settings/material_unit*'))
                                active open
                            @endif">
                            <a href="{{ route('material_unit.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/material_unit*'))
                                        style="color:#59deea;"
                                    @endif>單位設定</span>
                                @if(Request::is('settings/material_unit*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item  
                            @if(Request::is('settings/material_module*'))
                                active open
                            @endif">
                            <a href="{{ route('material_module.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/material_module*'))
                                        style="color:#59deea;"
                                    @endif>物料模組</span>
                                @if(Request::is('settings/material_module*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item  
                            @if(Request::is('settings/customers*'))
                                active open
                            @endif">
                            <a href="{{ route('customers.index') }}" class="nav-link ">
                                <span class="title" 
                                    @if(Request::is('settings/customers*'))
                                        style="color:#59deea;"
                                    @endif>客戶資料</span>
                                @if(Request::is('settings/customers*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('settings/warehouses*'))
                                active open
                            @endif">
                            <a href="{{ route('warehouses.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('settings/warehouses*'))
                                        style="color:#59deea;"
                                    @endif>倉儲資料</span>
                                @if(Request::is('settings/warehouses*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('settings/warehouse_category*'))
                                active open
                            @endif">
                            <a href="{{ route('warehouse_category.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('settings/warehouse_category*'))
                                        style="color:#59deea;"
                                    @endif>倉儲分類設定</span>
                                @if(Request::is('settings/warehouse_category*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        

                        {{-- <li class="nav-item  
                            @if(Request::is('settings/gallery*'))
                                active open
                            @endif">
                            <a href="#" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('settings/gallery*'))
                                        style="color:#59deea;"
                                    @endif>圖庫(建構中)</span>
                                @if(Request::is('settings/gallery*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li> --}}

                    </ul>
                </li>


                <li class="nav-item 
                    @if(Request::is('purchase*'))
                        active open
                    @endif ">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-basket"></i>
                        <span class="title">採購進貨</span>
                        <span class="arrow"></span>
                        @if(Request::is('purchase*'))
                            <span class="selected"></span>
                        @endif
                    </a>

                    <ul class="sub-menu">
                        <li class="nav-item  
                            @if(Request::is('purchase/inquiry*'))
                                active open
                            @endif">
                            <a href="{{ route('inquiry.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/inquiry*'))
                                        style="color:#59deea;"
                                    @endif>詢價</span>
                                @if(Request::is('purchase/inquiry*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('purchase/buy*'))
                                active open
                            @endif">
                            <a href="{{ route('buy.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/buy*'))
                                        style="color:#59deea;"
                                    @endif>採購</span>
                                @if(Request::is('purchase/buy*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('purchase/ibuy_to_stock*'))
                                active open
                            @endif">
                            <a href="{{ route('ibuy_to_stock.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/ibuy_to_stock*'))
                                        style="color:#59deea;"
                                    @endif>採購轉入庫中</span>
                                @if(Request::is('purchase/ibuy_to_stock*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('purchase/stock*'))
                                active open
                            @endif">
                            <a href="{{ route('stock.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/stock*'))
                                        style="color:#59deea;"
                                    @endif>入庫</span>
                                @if(Request::is('purchase/stock*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        
                        <li class="nav-item  
                            @if(Request::is('purchase/account_payable*'))
                                active open
                            @endif">
                            <a href="{{ route('account_payable.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/account_payable*'))
                                        style="color:#59deea;"
                                    @endif>應付帳款管理</span>
                                @if(Request::is('purchase/account_payable*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('purchase/payment_record*'))
                                active open
                            @endif">
                            <a href="{{ route('payment_record.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/payment_record*'))
                                        style="color:#59deea;"
                                    @endif>付款記錄</span>
                                @if(Request::is('purchase/payment_record*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('purchase/p_sales_return*'))
                                active open
                            @endif">
                            <a href="{{ route('p_sales_return.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/p_sales_return*'))
                                        style="color:#59deea;"
                                    @endif>採購退貨</span>
                                @if(Request::is('purchase/p_sales_return*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item  
                            @if(Request::is('purchase/p_exchange*'))
                                active open
                            @endif">
                            <a href="{{ route('p_exchange.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/p_exchange*'))
                                        style="color:#59deea;"
                                    @endif>採購換貨</span>
                                @if(Request::is('purchase/p_exchange*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        
                        <li class="nav-item  
                            @if(Request::is('purchase/monthly_report*'))
                                active open
                            @endif">
                            <a href="{{ route('monthly_report.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/monthly_report*'))
                                        style="color:#59deea;"
                                    @endif>月報表</span>
                                @if(Request::is('purchase/monthly_report*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li><li class="nav-item  
                            @if(Request::is('purchase/annual_report*'))
                                active open
                            @endif">
                            <a href="{{ route('annual_report.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('purchase/annual_report*'))
                                        style="color:#59deea;"
                                    @endif>年報表</span>
                                @if(Request::is('purchase/annual_report*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item 
                    @if(Request::is('shopping*'))
                        active open
                    @endif ">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-logout"></i>
                        <span class="title">銷貨出貨</span>
                        <span class="arrow"></span>
                        @if(Request::is('shopping*'))
                            <span class="selected"></span>
                        @endif
                    </a>

                    <ul class="sub-menu">
                        <li class="nav-item  
                            @if(Request::is('shopping/sale*'))
                                active open
                            @endif">
                            <a href="{{ route('sale.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('shopping/sale*'))
                                        style="color:#59deea;"
                                    @endif>銷貨</span>
                                @if(Request::is('shopping/sale*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('shopping/apply_out_stock*'))
                                active open
                            @endif">
                            <a href="{{ route('apply_out_stock.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('shopping/apply_out_stock*'))
                                        style="color:#59deea;"
                                    @endif>申請出庫</span>
                                @if(Request::is('shopping/apply_out_stock*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('shopping/picking*'))
                                active open
                            @endif">
                            <a href="{{ route('picking.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('shopping/picking*'))
                                        style="color:#59deea;"
                                    @endif>集貨撿貨</span>
                                @if(Request::is('shopping/picking*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('shopping/out_stock*'))
                                active open
                            @endif">
                            <a href="{{ route('out_stock.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('shopping/out_stock*'))
                                        style="color:#59deea;"
                                    @endif>出庫</span>
                                @if(Request::is('shopping/out_stock*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item  
                            @if(Request::is('shopping/account_receivable*'))
                                active open
                            @endif">
                            <a href="{{ route('account_receivable.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('shopping/account_receivable*'))
                                        style="color:#59deea;"
                                    @endif>應收帳款管理</span>
                                @if(Request::is('shopping/account_receivable*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('shopping/receivable_record*'))
                                active open
                            @endif">
                            <a href="{{ route('receivable_record.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('shopping/receivable_record*'))
                                        style="color:#59deea;"
                                    @endif>收款記錄</span>
                                @if(Request::is('shopping/receivable_record*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('shopping/s_sales_return*'))
                                active open
                            @endif">
                            <a href="{{ route('s_sales_return.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('shopping/s_sales_return*'))
                                        style="color:#59deea;"
                                    @endif>銷貨退貨</span>
                                @if(Request::is('shopping/s_sales_return*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item  
                            @if(Request::is('shopping/s_exchange*'))
                                active open
                            @endif">
                            <a href="{{ route('s_exchange.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('shopping/s_exchange*'))
                                        style="color:#59deea;"
                                    @endif>銷貨換貨</span>
                                @if(Request::is('shopping/s_exchange*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item  
                            @if(Request::is('shopping/prime_cost*'))
                                active open
                            @endif">
                            <a href="{{ route('prime_cost.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('shopping/prime_cost*'))
                                        style="color:#59deea;"
                                    @endif>成本利潤估算</span>
                                @if(Request::is('shopping/prime_cost*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item 
                    @if(Request::is('stock*'))
                        active open
                    @endif ">
                    <a href="javascript:;" class="nav-link nav-toggle">
                        <i class="icon-grid"></i>
                        <span class="title">庫存盤點</span>
                        <span class="arrow"></span>
                        @if(Request::is('stock*'))
                            <span class="selected"></span>
                        @endif
                    </a>

                    <ul class="sub-menu">
                        <li class="nav-item  
                            @if(Request::is('stock/inventory*'))
                                active open
                            @endif">
                            <a href="{{ route('inventory.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('stock/inventory*'))
                                        style="color:#59deea;"
                                    @endif>盤點</span>
                                @if(Request::is('stock/inventory*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item  
                            @if(Request::is('stock/adjustment*'))
                                active open
                            @endif">
                            <a href="{{ route('adjustment.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('stock/adjustment*'))
                                        style="color:#59deea;"
                                    @endif>誤差處理</span>
                                @if(Request::is('stock/adjustment*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item  
                            @if(Request::is('stock/transfer_inventory*'))
                                active open
                            @endif">
                            <a href="{{ route('transfer_inventory.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('stock/transfer_inventory*'))
                                        style="color:#59deea;"
                                    @endif>調撥</span>
                                @if(Request::is('stock/transfer_inventory*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('stock/on_order_follow*'))
                                active open
                            @endif">
                            <a href="{{ route('on_order_follow.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('stock/on_order_follow*'))
                                        style="color:#59deea;"
                                    @endif>在途量追蹤</span>
                                @if(Request::is('stock/on_order_follow*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('stock/semi_finished_schedule*'))
                                active open
                            @endif">
                            <a href="{{ route('semi_finished_schedule.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('stock/semi_finished_schedule*'))
                                        style="color:#59deea;"
                                    @endif>半成品進度追蹤</span>
                                @if(Request::is('stock/semi_finished_schedule*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item  
                            @if(Request::is('stock/residual_material_processing*'))
                                active open
                            @endif">
                            <a href="{{ route('residual_material_processing.index') }}" class="nav-link ">
                                <span class="title"
                                    @if(Request::is('stock/residual_material_processing*'))
                                        style="color:#59deea;"
                                    @endif>餘料處理</span>
                                @if(Request::is('stock/residual_material_processing*'))
                                    <span class="selected"></span>
                                @endif
                            </a>
                        </li>


                        
                        
                        
                    </ul>
                </li>
            </ul>
            <!-- END SIDEBAR MENU -->
            <!-- END SIDEBAR MENU -->
        </div>
        <!-- END SIDEBAR -->
    </div>
    <!-- END SIDEBAR -->