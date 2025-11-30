<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    {{ auth()->user()->name ?? 'Guest' }}
                </span>

                {{-- LOGIKA PENAMPILAN FOTO --}}
                @php
                    $userPhoto = auth()->user()->photo;
                    // Cek apakah file fisik ada di public/photos/nama_file
                    $photoExists = $userPhoto && file_exists(public_path('photos/' . $userPhoto));
                @endphp

                @if($photoExists)
                    {{-- Foto ditemukan, tampilkan --}}
                    <img class="img-profile rounded-circle"
                        src="{{ asset('photos/' . $userPhoto) }}"
                        style="width: 32px; height: 32px; object-fit: cover; border: 1px solid #e3e6f0;">
                @else
                    {{-- Foto tidak ada/hilang, pakai default --}}
                    <img class="img-profile rounded-circle"
                        src="{{ asset('sb-admin-2/img/undraw_profile.svg') }}"
                        style="width: 32px; height: 32px; object-fit: cover;">
                @endif

            </a>

            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="dropdown-item" type="submit">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </button>
                </form>
            </div>
        </li>

    </ul>

</nav>
