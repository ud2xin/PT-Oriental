<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ auth()->user()->name ?? 'Guest' }}</span>
            <img class="img-profile rounded-circle" src="{{ asset('sb-admin-2/img/undraw_profile.svg') }}" style="width:32px">
        </a>
        <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in">
            <a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>Profile</a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="dropdown-item">Logout</button>
            </form>
        </div>
        </li>
    </ul>
</nav>
