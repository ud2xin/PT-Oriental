@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="container-fluid">

    <h3 class="mb-4">Tambah User</h3>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="karyawan">Karyawan</option>
                <option value="super_admin">Super Admin</option>
            </select>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection