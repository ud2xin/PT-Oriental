@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h3>Manajemen User</h3>

        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Tambah User
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">

            {{-- Search --}}
            <form method="GET" action="{{ route('users.index') }}" class="form-inline mb-3">
                <div class="input-group w-100">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..."
                        value="{{ request('search') }}">

                    <div class="input-group-append">
                        <button class="btn btn-info">Cari</button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary ml-1">Reset</a>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>

                            <td>
                                <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">
                                    Detail
                                </a>

                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    Edit
                                </a>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {!! $users->links() !!}
            </div>

        </div>
    </div>

</div>
@endsection