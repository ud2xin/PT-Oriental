@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
<div class="container-fluid">

    <h3 class="mb-4">Detail User</h3>

    <table class="table table-bordered w-50">
        <tr>
            <th>No</th>
            <td>{{ $user->id }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>Role</th>
            <td>{{ $user->role }}</td>
        </tr>
    </table>

    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>

</div>
@endsection