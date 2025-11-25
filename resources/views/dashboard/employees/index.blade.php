@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">

    <h2 class="text-xl font-bold mb-4">Dashboard Karyawan</h2>

    <div class="bg-white shadow rounded p-4 mb-6">
        <h3 class="font-semibold text-lg mb-3">Profil Karyawan</h3>
        <p><strong>NIP:</strong> {{ $karyawan->empl_number }}</p>
        <p><strong>Nama:</strong> {{ $karyawan->korx_name }}</p>
        <p><strong>Jabatan:</strong> {{ $karyawan->jjik_part }}</p>
        <p><strong>Departemen:</strong> {{ $karyawan->dept_name }}</p>
    </div>

    <div class="bg-white shadow rounded p-4">
        <h3 class="font-semibold text-lg mb-3">Riwayat Absensi</h3>

        <table class="table w-full table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absensi as $item)
                <tr>
                    <td>{{ $item->date_part }}</td>
                    <td>{{ $item->in_time }}</td>
                    <td>{{ $item->out_time }}</td>
                    <td>{{ $item->status_part }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
