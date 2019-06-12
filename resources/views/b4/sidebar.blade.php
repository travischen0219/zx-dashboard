<div id="sidebar">
    <a id="sidebar-brand" href="javascript: void(0);">
        <span id="sidebar-name" class="mr-1">真心蓮坊進銷存</span>
        <i class="fas fa-bars"></i>
    </a>

    <ul>
        <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>
                <span>首頁</span>
            </a>
        </li>
        <li {{ Request::is('settings*') ? 'active' : '' }}">
            <a href="#">
                <i class="fas fa-cog"></i>
                <span class="sidebar-title">基本資料</span>
            </a>

            <ul>
                @foreach (App\Model\Sidebar::settings() as $item)
                <li>
                    <a href="{{ route($item['route']) }}">{{ $item['title'] }}</a>
                </li>
                @endforeach
            </ul>
        </li>
        <li {{ Request::is('purchase*') ? 'active' : '' }}">
            <a href="#">
                <i class="fas fa-shopping-cart"></i>
                <span class="sidebar-title">採購進貨</span>
            </a>
        </li>
        <li {{ Request::is('shopping*') ? 'active' : '' }}">
            <a href="#">
                <i class="fas fa-dolly-flatbed"></i>
                <span class="sidebar-title">銷貨出貨</span>
            </a>
        </li>
        <li {{ Request::is('stock*') ? 'active' : '' }}">
            <a href="#">
                <i class="fas fa-cube"></i>
                <span class="sidebar-title">庫存盤點</span>
            </a>
        </li>
    </ul>

    <div id="sidebar-account">
        {{ session()->has('admin_user') ? session('admin_user')->fullname : '查無登入者' }}
        <div><a href="{{ route('logout') }}">登出</a></div>
    </div>
</div>
