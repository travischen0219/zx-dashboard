<nav id="navbar-main" class="navbar navbar-expand-sm justify-content-between">
    <a class="navbar-brand" href="javascript: void(0);">
        <span id="navbar-name" class="mr-1">真心蓮坊進銷存</span>
        <i class="fas fa-bars"></i>
    </a>

    <ul class="navbar-nav mr-3">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                {{ session()->has('admin_user') ? session('admin_user')->fullname : '查無登入者' }}
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('logout') }}">登出</a>
            </div>
        </li>
    </ul>
</nav>
