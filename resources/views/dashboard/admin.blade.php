@extends('layouts.app')
@section('title', 'Dashboard - Admin Departemen')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard Admin - Departemen {{ $departemen }}</h1>
</div>

<!-- Stats Cards Row -->
<div class="row">
    <!-- Total Anggota -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Anggota</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAnggota }}</div>
                        <div class="text-xs text-muted mt-1">Departemen {{ $departemen }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hadir Hari Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir Hari Ini</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $hadirHariIni }}</div>
                        <div class="text-xs text-primary mt-1">{{ number_format($persentaseKehadiran, 1) }}% kehadiran</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Izin -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Izin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $izinHariIni }}</div>
                        <div class="text-xs text-muted mt-1">Karyawan izin hari ini</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alfa -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Alfa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $alfaHariIni }}</div>
                        <div class="text-xs text-danger mt-1">Tidak hadir tanpa keterangan</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Kehadiran Departemen {{ $departemen }}</h6>
            </div>
            <div class="card-body">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <a href="{{ route('attendance.index') }}" class="btn btn-primary btn-block mb-3">
                    <i class="fas fa-eye mr-2"></i> Lihat Detail Absensi
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-success btn-block mb-3">
                    <i class="fas fa-users-cog mr-2"></i> Kelola Karyawan
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-info btn-block">
                    <i class="fas fa-chart-line mr-2"></i> Laporan Departemen
                </a>
            </div>
        </div>

        <!-- Performance Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Performa Minggu Ini</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">
                    Kehadiran
                    <span class="float-right">{{ number_format($persentaseKehadiran, 1) }}%</span>
                </h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $persentaseKehadiran }}%"></div>
                </div>

                <div class="text-center">
                    <i class="fas fa-trophy text-warning fa-3x mb-2"></i>
                    <p class="text-muted mb-0">Departemen {{ $departemen }}</p>
                    <p class="font-weight-bold">Performa Baik!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table: Riwayat Kehadiran Hari Ini -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Riwayat Kehadiran Departemen Hari Ini</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Karyawan</th>
                                <th>Jam Masuk</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kehadiranHariIni as $index => $kehadiran)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="font-weight-bold">{{ $kehadiran['nama'] }}</td>
                                <td>{{ $kehadiran['jam_masuk'] }}</td>
                                <td>
                                    @if($kehadiran['status'] === 'Hadir')
                                        <span class="badge badge-success">{{ $kehadiran['status'] }}</span>
                                    @elseif($kehadiran['status'] === 'Izin')
                                        <span class="badge badge-warning">{{ $kehadiran['status'] }}</span>
                                    @else
                                        <span class="badge badge-danger">{{ $kehadiran['status'] }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-right mt-3">
                    <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-primary">
                        Lihat Selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($chartLabels);
    const dataHadir = @json($chartHadir);

    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Hadir',
                data: dataHadir,
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 10
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
</script>
@endpush
