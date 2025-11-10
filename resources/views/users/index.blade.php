@extends('layouts.app')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-bold text-blue-600 mb-6">Manajemen User</h1>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-blue-500 text-white text-left">
                <th class="py-3 px-4">Nama</th>
                <th class="py-3 px-4">Email</th>
                <th class="py-3 px-4">Role</th>
                <th class="py-3 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-b hover:bg-blue-50">
                    <td class="py-3 px-4">{{ $user->name }}</td>
                    <td class="py-3 px-4">{{ $user->email }}</td>
                    <td class="py-3 px-4 capitalize">{{ $user->role }}</td>
                    <td class="py-3 px-4">
                        <button class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
