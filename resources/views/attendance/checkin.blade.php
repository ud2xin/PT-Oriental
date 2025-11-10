@extends('layouts.app')
@section('title', 'Absen Sekarang')

@push('styles')
<style>
    .clock-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }

    .digital-clock {
        font-size: 4rem;
        font-weight: 700;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        font-family: 'Courier New', monospace;
    }

    .date-display {
        font-size: 1.5rem;
        color: rgba(255,255,255,0.9);
        font-weight: 500;
    }

    .attendance-btn {
        padding: 20px 50px;
        font-size: 1.5rem;
        font-weight: 700;
        border-radius: 50px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .attendance-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    }

    .status-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .pulse {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
</style>
@endpush

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Absen Sekarang</h1>
    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">
        <i class="fas fa-history mr-2"></i>Lihat Riwayat
    </a>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    <button type="button" class="close" data-dismiss="alert">
        <span>&times;</span>
    </button>
</div>
@endif

<!-- Clock Card -->
<div class="card shadow mb-4">
    <div class="card-body p-0">
        <div class="clock-container text-center">
            <div class="date-display mb-3" id="dateDisplay"></div>
            <div class="digital-clock" id="digitalClock"></div>
            <div class="mt-3 text-white">
                <i class="fas fa-map-marker-alt mr-2"></i>
                <span id="locationDisplay">Jakarta, Indonesia</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Check In/Out Section -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow status-card">
            <div class="card-body text-center p-5">
                @if(!$todayAttendance['checked_in'])
                    <!-- Belum Check In -->
                    <div class="mb-4">
                        <i class="fas fa-hand-point-right fa-5x text-primary mb-3 pulse"></i>
                        <h3 class="font-weight-bold text-gray-800">Selamat Datang!</h3>
                        <p class="text-muted">Silakan lakukan check-in untuk memulai hari kerja Anda</p>
                    </div>

                    <form action="{{ route('attendance.checkin.post') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success attendance-btn">
                            <i class="fas fa-sign-in-alt mr-2"></i>Check In
                        </button>
                    </form>

                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="fas fa-clock mr-1"></i>
                            Jam kerja: 08:00 - 17:00
                        </small>
                    </div>

                @elseif(!$todayAttendance['checked_out'])
                    <!-- Sudah Check In, Belum Check Out -->
                    <div class="mb-4">
                        <i class="fas fa-user-check fa-5x text-success mb-3"></i>
                        <h3 class="font-weight-bold text-success">Sudah Check In</h3>
                        <p class="text-muted">Anda masuk pada pukul <strong>{{ $todayAttendance['check_in_time'] }}</strong></p>
                    </div>

                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Jangan lupa untuk check-out saat pulang ya!
                    </div>

                    <form action="{{ route('attendance.checkout.post') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger attendance-btn">
                            <i class="fas fa-sign-out-alt mr-2"></i>Check Out
                        </button>
                    </form>

                @else
                    <!-- Sudah Check In dan Check Out -->
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                        <h3 class="font-weight-bold text-success">Absensi Hari Ini Selesai!</h3>
                        <div class="mt-3">
                            <p class="mb-2">Check In: <strong>{{ $todayAttendance['check_in_time'] }}</strong></p>
                            <p>Check Out: <strong>{{ $todayAttendance['check_out_time'] }}</strong></p>
                        </div>
                    </div>

                    <div class="alert alert-success">
                        <i class="fas fa-thumbs-up mr-2"></i>
                        Terima kasih sudah bekerja keras hari ini! Sampai jumpa besok.
                    </div>

                    <a href="{{ route('attendance.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-list mr-2"></i>Lihat Riwayat Absensi
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Info Section -->
    <div class="col-lg-4 mb-4">
        <!-- Status Today -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Status Hari Ini</h6>
            </div>
            <div class="card-body">
                @if(!$todayAttendance['checked_in'])
                    <div class="text-center">
                        <i class="fas fa-hourglass-start fa-3x text-warning mb-3"></i>
                        <h5 class="text-warning">Belum Absen</h5>
                        <p class="text-muted mb-0">Jangan lupa check-in ya!</p>
                    </div>
                @elseif(!$todayAttendance['checked_out'])
                    <div class="text-center">
                        <i class="fas fa-briefcase fa-3x text-info mb-3"></i>
                        <h5 class="text-info">Sedang Bekerja</h5>
                        <p class="text-muted mb-0">Masuk: {{ $todayAttendance['check_in_time'] }}</p>
                    </div>
                @else
                    <div class="text-center">
                        <i class="fas fa-home fa-3x text-success mb-3"></i>
                        <h5 class="text-success">Sudah Selesai</h5>
                        <p class="text-muted mb-0">Selamat beristirahat!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Jam Kerja Info -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Jam Kerja</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <i class="fas fa-clock text-primary mr-2"></i>
                    <strong>Senin - Jumat</strong>
                    <p class="ml-4 mb-0 text-muted">08:00 - 17:00</p>
                </div>
                <hr>
                <div class="mb-3">
                    <i class="fas fa-clock text-primary mr-2"></i>
                    <strong>Sabtu</strong>
                    <p class="ml-4 mb-0 text-muted">08:00 - 12:00</p>
                </div>
                <hr>
                <div>
                    <i class="fas fa-calendar-times text-danger mr-2"></i>
                    <strong>Minggu & Libur</strong>
                    <p class="ml-4 mb-0 text-muted">Tidak ada jam kerja</p>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Menu Lainnya</h6>
            </div>
            <div class="card-body p-2">
                <a href="{{ route('attendance.index') }}" class="btn btn-light btn-block text-left">
                    <i class="fas fa-history mr-2"></i>Riwayat Absensi
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-light btn-block text-left">
                    <i class="fas fa-chart-bar mr-2"></i>Laporan Kehadiran
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-block text-left">
                    <i class="fas fa-home mr-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Real-time Clock
    function updateClock() {
        const now = new Date();

        // Update digital clock
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById('digitalClock').textContent = `${hours}:${minutes}:${seconds}`;

        // Update date
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateStr = now.toLocaleDateString('id-ID', options);
        document.getElementById('dateDisplay').textContent = dateStr;
    }

    // Update clock every second
    setInterval(updateClock, 1000);
    updateClock(); // Initial call

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endpush
