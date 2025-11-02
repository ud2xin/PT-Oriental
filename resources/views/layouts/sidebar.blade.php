@php $role = auth()->user()->role ?? 'guest'; @endphp
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon"><i class="fas fa-id-badge"></i></div>
        <div class="sidebar-brand-text mx-3">Oriental Absensi</div>
    </a>

    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}"><i class="fas fa-fw fa-tachometer-alt"></i><span>Dashboard</span></a>
    </li>

    @if(in_array($role,['admin','super_admin']))
    <li class="nav-item"><a class="nav-link" href="{{ route('import.index') }}"><i class="fas fa-file-import"></i><span>Import Data</span></a></li>
    @endif

    <li class="nav-item"><a class="nav-link" href="{{ route('attendance.index') }}"><i class="fas fa-table"></i><span>Data Absensi</span></a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('reports.index') }}"><i class="fas fa-chart-area"></i><span>Laporan</span></a></li>

    @if($role === 'super_admin')
        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}"><i class="fas fa-users"></i><span>Manajemen User</span></a></li>
        <li class="nav-item"><a class="nav-link" href="{{ route('settings.index') }}"><i class="fas fa-cogs"></i><span>Konfigurasi</span></a></li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">
</ul>
