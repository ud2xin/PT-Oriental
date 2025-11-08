@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-xl font-semibold mb-4">{{ $title }}</h1>
    <ul>
        <li><strong>Nama:</strong> {{ $nama }}</li>
        <li><strong>Departemen:</strong> {{ $departemen }}</li>
        <li><strong>Tanggal:</strong> {{ $tanggal }}</li>
        <li><strong>Jam Masuk:</strong> {{ $jam_masuk }}</li>
        <li><strong>Jam Keluar:</strong> {{ $jam_keluar }}</li>
        <li><strong>Status:</strong> {{ $status }}</li>
    </ul>
</div>
@endsection
