@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">

    <h3 class="mb-4">Edit User</h3>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="karyawan" {{ $user->role=='karyawan'?'selected':'' }}>Karyawan</option>
                <option value="super_admin" {{ $user->role=='super_admin'?'selected':'' }}>Super Admin</option>
            </select>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
    </form>

</div>
@endsection