@php
    $role = auth()->user()->role ?? 'guest';
    $currentRoute = Route::currentRouteName();
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand with Logo -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('images/oei-logo.png') }}" alt="OEI Logo" style="max-height: 40px; max-width: 40px;">
        </div>
        <div class="sidebar-brand-text mx-2">PT ORIENTAL</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading - Absensi -->
    <div class="sidebar-heading">
        Absensi
    </div>

    <!-- Nav Item - Data Absensi -->
    <li class="nav-item {{ request()->is('attendance') || request()->is('attendance/history') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('attendance.index') }}">
            <i class="fas fa-fw fa-table"></i>
            <span>Data Absensi</span>
        </a>
    </li>

    <!-- Nav Item - Laporan -->
    <li class="nav-item {{ request()->is('reports*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('reports.index') }}">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Laporan</span>
        </a>
    </li>

    @if(in_array($role, ['admin', 'super_admin']))
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading - Management -->
    <div class="sidebar-heading">
        Manajemen
    </div>

    <!-- Nav Item - Karyawan -->
    <li class="nav-item {{ request()->is('employees*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('employees.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Data Karyawan</span>
        </a>
    </li>

    <!-- Nav Item - Departemen (Admin/Super Admin) -->
    {{-- <li class="nav-item {{ request()->is('departments*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('departments.index') }}">
            <i class="fas fa-fw fa-building"></i>
            <span>Departemen</span>
        </a>
    </li> --}}

    <!-- Nav Item - Import Data -->
    <li class="nav-item {{ request()->is('import*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('import.index') }}">
            <i class="fas fa-fw fa-file-import"></i>
            <span>Import Data</span>
        </a>
    </li>
    @endif

    @if($role === 'super_admin')
    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading - Admin -->
    <div class="sidebar-heading">
        Administrator
    </div>

    <!-- Nav Item - Manajemen User -->
    <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('users.index') }}">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>Manajemen User</span>
        </a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    {{-- <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div> --}}

    <!-- Sidebar Message (Optional - bisa dihapus jika tidak perlu) -->
    <div class="sidebar-card d-none d-lg-flex">
        <img class="sidebar-card-illustration mb-2" src="{{ asset('images/oei-logo.png') }}" alt="...">
        <p class="text-center mb-2"><strong>Sistem Absensi</strong> PT Oriental Electronics Indonesia</p>
    </div>

</ul>

<style>
/* Custom Sidebar Styling */
.sidebar .sidebar-brand {
    height: 4.375rem;
    text-decoration: none;
    font-size: 1rem;
    font-weight: 800;
    padding: 1.5rem 1rem;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.05rem;
    z-index: 1;
}

.sidebar .sidebar-brand-icon img {
    filter: brightness(0) invert(1); /* Makes logo white */
}

.sidebar-heading {
    text-align: center;
    padding: 0 1rem;
    font-weight: 800;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.1rem;
}

.sidebar .nav-item.active .nav-link {
    font-weight: 700;
}

.sidebar-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 0.875rem;
    border-radius: 0.35rem;
    background-color: rgba(0, 0, 0, 0.1);
    margin: 1.5rem;
    padding: 1rem;
}

.sidebar-card-illustration {
    height: 3rem;
}
</style>
