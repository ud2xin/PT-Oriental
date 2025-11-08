@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Karyawan</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Departemen</th>
                <th>Posisi</th>
                <th>Telepon</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->department->name ?? '-' }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->phone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
