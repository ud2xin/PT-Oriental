@extends('layouts.app')
@section('title', 'Dashboard - Karyawan')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Selamat Datang, {{ $nama }}!</h1>
</div>

<!-- Status Card -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow border-left-primary h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="font-weight-bold text-primary mb-3">Status Kehadiran Hari Ini</h5>
                        @if($jamCheckOut)
                            <h3 class="text-success mb-2">
                                <i class="fas fa-check-circle mr-2"></i>Sudah Check Out
                            </h3>
                            <p class="text-muted mb-1">Check In: {{ $jamCheckIn }}</p>
                            <p class="text-muted">Check Out: {{ $jamCheckOut }}</p>
                        @elseif($jamCheckIn)
                            <h3 class="text-info mb-2">
                                <i class="fas fa-clock mr-2"></i>{{ $statusHariIni }}
                            </h3>
                            <p class="text-muted">Jam Masuk: {{ $jamCheckIn }}</p>
                            <p class="text-warning">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Jangan lupa check out ya!
                            </p>
                        @else
                            <h3 class="text-warning mb-2">
                                <i class="fas fa-exclamation-circle mr-2"></i>Belum Check In
                            </h3>
                            <p class="text-muted">Anda belum melakukan absensi hari ini</p>
                        @endif
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-5x text-gray-300"></i>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <a href="{{ route('attendance.index') }}" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-fingerprint mr-2"></i>
                        @if(!$jamCheckIn)
                            Absen Sekarang
                        @elseif(!$jamCheckOut)
                            Check Out Sekarang
                        @else
                            Lihat Riwayat Absensi
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bulan Ini -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Progress Bulan Ini</h6>
            </div>
            <div class="card-body">
                <canvas id="progressChart"></canvas>
                <hr>
                <div class="text-center">
                    <div class="row">
                        <div class="col-4">
                            <h6 class="text-success font-weight-bold">{{ $hadirBulanIni }}</h6>
                            <small class="text-muted">Hadir</small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-warning font-weight-bold">{{ $izinBulanIni }}</h6>
                            <small class="text-muted">Izin</small>
                        </div>
                        <div class="col-4">
                            <h6 class="text-danger font-weight-bold">{{ $alfaBulanIni }}</h6>
                            <small class="text-muted">Alfa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Singkat -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat 5 Hari Terakhir</h6>
                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-primary">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($riwayatTerakhir as $riwayat)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($riwayat['tanggal'])->format('d M Y') }}</td>
                                <td>{{ $riwayat['jam_masuk'] }}</td>
                                <td>{{ $riwayat['jam_keluar'] }}</td>
                                <td>
                                    @if($riwayat['status'] === 'Hadir')
                                        <span class="badge badge-success">{{ $riwayat['status'] }}</span>
                                    @elseif($riwayat['status'] === 'Izin')
                                        <span class="badge badge-warning">{{ $riwayat['status'] }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $riwayat['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Info Cards -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Hadir</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hadirBulanIni }}/{{ $totalHariKerja }}</div>
                        <div class="text-xs text-muted mt-1">
                            {{ number_format(($hadirBulanIni/$totalHariKerja)*100, 1) }}% dari hari kerja
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Izin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $izinBulanIni }}</div>
                        <div class="text-xs text-muted mt-1">Hari izin bulan ini</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Alfa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $alfaBulanIni }}</div>
                        <div class="text-xs text-muted mt-1">Hari tanpa keterangan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const hadir = {{ $hadirBulanIni }};
    const izin = {{ $izinBulanIni }};
    const alfa = {{ $alfaBulanIni }};

    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin', 'Alfa'],
            datasets: [{
                data: [hadir, izin, alfa],
                backgroundColor: [
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(246, 194, 62, 0.8)',
                    'rgba(231, 74, 59, 0.8)'
                ],
                borderColor: [
                    'rgba(28, 200, 138, 1)',
                    'rgba(246, 194, 62, 1)',
                    'rgba(231, 74, 59, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
