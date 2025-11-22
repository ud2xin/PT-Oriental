@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen User</h1>
        <div>
            <a href="{{ route('users.create') ?? '#' }}" class="btn btn-primary">
                <i class="fas fa-user-plus mr-1"></i>Tambah User
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Search form -->
            <form method="GET" action="{{ route('users.index') }}" class="form-inline mb-3">
                <div class="input-group w-100">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama, email atau PIN..." value="{{ old('search', $search ?? request('search')) }}">
                    <div class="input-group-append">
                        <button class="btn btn-info" type="submit">
                            <i class="fas fa-search"></i> Cari
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary ml-1">Reset</a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Departemen</th>
                            <th>Role</th>
                            <th>PIN</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $users->firstItem() + $index }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->department->name ?? '-' }}</td>
                                <td>{{ $user->role }}</td>
                                <td>{{ $user->pin ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('users.show', $user->id) ?? '#' }}" class="btn btn-sm btn-info">Detail</a>
                                    <a href="{{ route('users.edit', $user->id) ?? '#' }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('users.destroy', $user->id) ?? '#' }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data user</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} user
                </div>
                <div>
                    {{-- Render pagination links; withQueryString() sudah memastikan search tetap ada --}}
                    {!! $users->links() !!}
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
