@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-xl shadow-md p-6 mt-8">
    <h2 class="text-2xl font-semibold mb-4 text-center">Check-Out</h2>

    <p class="text-gray-600 text-center mb-4">Sampai jumpa, {{ $user->name ?? 'Karyawan' }} 👋</p>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('attendance.checkout') }}" method="POST">
        @csrf
        <div class="text-center">
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition">
                Check-Out Sekarang
            </button>
        </div>
    </form>
</div>
@endsection
