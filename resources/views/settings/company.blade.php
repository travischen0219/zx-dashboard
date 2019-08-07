<style>
#page-header {
    border: none !important;
}
</style>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link {{ Request::is('settings/staff*') ? 'active' : '' }}" href="{{ route('staff.index') }}">
            <i class="fas fa-users active-color"></i> 員工資料
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('settings/department*') ? 'active' : '' }}" href="{{ route('department.index') }}">
            <i class="fas fa-building active-color"></i> 部門設定
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ Request::is('settings/professional_title*') ? 'active' : '' }}" href="{{ route('professional_title.index') }}">
            <i class="fas fa-id-card-alt active-color"></i> 職稱設定
        </a>
    </li>
</ul>
