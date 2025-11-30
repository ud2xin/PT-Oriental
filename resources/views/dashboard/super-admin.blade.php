@extends('layouts.app')
@section('title', 'Dashboard - Super Admin')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h4 class="font-weight-bold mb-0 text-primary">Dashboard Super Admin</h4>
</div>

<!-- Stats Cards Row -->
<div class="row">
    <!-- Total Karyawan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Karyawan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKaryawan }}</div>
                        <div class="text-xs text-success mt-1">Jumlah karyawan</div>
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
                        <div class="text-xs text-primary mt-1">{{ number_format($persentaseKehadiran, 1) }}% kehadiran
                        </div>
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
                        <div class="text-xs text-muted mt-1">{{ number_format(($izinHariIni/$totalKaryawan)*100, 1) }}%
                            dari total</div>
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
                        <div class="text-xs text-danger mt-1">{{ number_format(($alfaHariIni/$totalKaryawan)*100, 1) }}%
                            dari total</div>
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
                <h6 class="m-0 font-weight-bold text-primary">Grafik Kehadiran Mingguan</h6>
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
                    <i class="fas fa-clock mr-2"></i> Lihat Data Absensi
                </a>
                <a href="{{ route('users.index') }}" class="btn btn-success btn-block mb-3">
                    <i class="fas fa-user-plus mr-2"></i> Kelola Karyawan
                </a>
                <a href="{{ route('import.index') }}" class="btn btn-info btn-block mb-3">
                    <i class="fas fa-file-import mr-2"></i> Import Excel
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-warning btn-block">
                    <i class="fas fa-chart-bar mr-2"></i> Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Statistics Summary -->
        {{-- <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Statistik Global</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="small text-gray-500 mb-1">Total Departemen</div>
                    <h4 class="font-weight-bold">{{ $totalDepartemen }}</h4>
    </div>
    <hr>
    <div class="mb-3">
        <div class="small text-gray-500 mb-1">Hari Kerja Bulan Ini</div>
        <h4 class="font-weight-bold">{{ $hariKerjaBulanIni }}</h4>
    </div>
    <hr>
    <div>
        <div class="small text-gray-500 mb-1">Terlambat Hari Ini</div>
        <h4 class="font-weight-bold text-warning">{{ $terlatHariIni }}</h4>
    </div>
</div>
</div>
</div> --}}
</div>

<!-- Table: 10 Karyawan Terlambat -->
{{-- <div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">10 Karyawan Terlambat Hari Ini</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Departemen</th>
                                <th>Jam Masuk</th>
                                <th>Keterlambatan</th>
                            </tr>
                        </thead>
                        <tbody> --}}
{{-- @foreach($karyawanTerlambat as $index => $karyawan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
<td class="font-weight-bold">{{ $karyawan['nama'] }}</td>
<td>{{ $karyawan['departemen'] }}</td>
<td>{{ $karyawan['jam_masuk'] }}</td>
<td>
    @php
    $menit = (int) filter_var($karyawan['keterlambatan'], FILTER_SANITIZE_NUMBER_INT);
    $badgeClass = $menit > 15 ? 'badge-danger' : 'badge-warning';
    @endphp
    <span class="badge {{ $badgeClass }}">{{ $karyawan['keterlambatan'] }}</span>
</td>
</tr>
@endforeach --}}
{{-- </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($labels);
    const dataHadir = @json($dataHadir);

    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Hadir',
                data: dataHadir,
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush