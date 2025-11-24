@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Laporan Overtime</h3>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Shift</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Lembur (Jam)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($overtimeData as $row)
            <tr>
                <td>{{ $row['tanggal'] }}</td>
                <td>{{ $row['nip'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['shift'] }}</td>
                <td>{{ $row['jam_masuk'] }}</td>
                <td>{{ $row['jam_keluar'] }}</td>
                <td>{{ $row['overtime'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection