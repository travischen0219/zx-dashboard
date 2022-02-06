@php
    $routeNamePrefix = \Route::current()->action['as'];
    $routeNamePrefix = explode('.', $routeNamePrefix)[0]
@endphp

<div id="sidebar">
    <a id="sidebar-brand" href="javascript: void(0);">
        <span id="sidebar-name" class="extend mr-1">真心蓮坊進銷存</span>
        <i id="sidebar-toggler" class="fas fa-bars"></i>
    </a>

    <ul>
        <li class="{{ Request::is('dashboard*') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>
                <span class="extend">首頁</span>
            </a>
        </li>

        @if (\App\Model\User::canView('admin'))
            <li class="{{ Request::is('settings/staff*') || Request::is('settings/access*') ? 'active' : '' }}">
                <a href="{{ route('staff.index') }}">
                    <i class="fas fa-key"></i>
                    <span>公司資料</span>
                </a>
            </li>
        @endif

        @if (\App\Model\User::canView('settings'))
            <li class="{{ Request::is('settings*') && !Request::is('settings/staff*') && !Request::is('settings/access*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fas fa-cog"></i>
                    <span class="extend">基本資料</span>
                </a>

                <ul>
                    @foreach (App\Model\Sidebar::settings() as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" class="{{ explode('.', $item['route'])[0] == $routeNamePrefix ? 'active' : '' }}">
                                {!! $item['title'] !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        @if (\App\Model\User::canView('purchase'))
            <li class="{{ Request::is('purchase*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="extend">採購進貨</span>
                </a>

                <ul>
                    @foreach (App\Model\Sidebar::purchases() as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                                target="{{ $item['target'] ?? '_self' }}"
                                class="{{ explode('.', $item['route'])[0] == $routeNamePrefix ? 'active' : '' }}">
                                {!! $item['title'] !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

        @if (\App\Model\User::canView('shopping'))
            <li class="{{ Request::is('shopping*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fas fa-dolly-flatbed"></i>
                    <span class="extend">銷貨出貨</span>
                </a>
                <ul>
                    @foreach (App\Model\Sidebar::shoppings() as $item)
                        @if ($item['route'] !== 'out.index')
                            @if (App\Model\User::canAdmin('shopping'))
                                <li>
                                    <a href="{{ route($item['route']) }}"
                                        target="{{ $item['target'] ?? '_self' }}"
                                        class="{{ explode('.', $item['route'])[0] == $routeNamePrefix ? 'active' : '' }}">
                                        {!! $item['title'] !!}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li>
                                <a href="{{ route($item['route']) }}"
                                    target="{{ $item['target'] ?? '_self' }}"
                                    class="{{ explode('.', $item['route'])[0] == $routeNamePrefix ? 'active' : '' }}">
                                    {!! $item['title'] !!}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </li>
        @endif

        @if (\App\Model\User::canView('stock'))
            <li class="{{ Request::is('stock*') ? 'active' : '' }}">
                <a href="#">
                    <i class="fas fa-cube"></i>
                    <span class="extend">庫存盤點</span>
                </a>
                <ul>
                    @foreach (App\Model\Sidebar::stocks() as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                                target="{{ $item['target'] ?? '_self' }}"
                                class="{{ explode('.', $item['route'])[0] == $routeNamePrefix ? 'active' : '' }}">
                                {!! $item['title'] !!}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif

    </ul>

    <div id="sidebar-account">
        <div id="sidebar-user" class="extend">
            {{ session()->has('admin_user') ? session('admin_user')->fullname : '查無登入者' }}
        </div>
        <div><a href="{{ route('logout') }}">
            <i class="fas fa-sign-out-alt"></i>
            <span class="extend">登出</span>
        </a></div>
    </div>
</div>
