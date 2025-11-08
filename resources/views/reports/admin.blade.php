@extends('layouts.app')
@section('title', 'Laporan Departemen')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Departemen {{ $userDepartemen }}</h1>
    <a href="{{ route('reports.export') }}" class="btn btn-success">
        <i class="fas fa-download mr-2"></i>Export Excel
    </a>
</div>

<!-- Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Periode</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-control">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-5 mb-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control">
                        @for($y = date('Y'); $y >= date('Y')-3; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Anggota</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_karyawan'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Hadir</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($summary['total_hadir']) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Izin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_izin'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Alfa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_alfa'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart & Summary -->
<div class="row mb-4">
    <div class="col-xl-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Kehadiran Departemen - {{ $namaBulan }}</h6>
            </div>
            <div class="card-body">
                <canvas id="reportsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Performa Departemen</h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                    <h3 class="font-weight-bold text-primary">{{ number_format($summary['rata_rata_kehadiran'], 1) }}%</h3>
                    <p class="text-muted">Rata-rata Kehadiran</p>
                </div>

                <hr>

                <div class="mb-3">
                    <i class="fas fa-calendar-check text-success mr-2"></i>
                    <strong>Hari Kerja:</strong> {{ $summary['total_hari_kerja'] }} hari
                </div>
                <div class="mb-3">
                    <i class="fas fa-clock text-warning mr-2"></i>
                    <strong>Total Terlambat:</strong> {{ $summary['total_terlambat'] }} kali
                </div>

                <hr>

                <h4 class="small font-weight-bold">
                    Target Kehadiran (95%)
                    <span class="float-right">
                        @if($summary['rata_rata_kehadiran'] >= 95)
                            <i class="fas fa-check text-success"></i> Tercapai
                        @else
                            {{ number_format(95 - $summary['rata_rata_kehadiran'], 1) }}% lagi
                        @endif
                    </span>
                </h4>
                <div class="progress">
                    <div class="progress-bar {{ $summary['rata_rata_kehadiran'] >= 95 ? 'bg-success' : 'bg-warning' }}"
                         role="progressbar"
                         style="width: {{ min($summary['rata_rata_kehadiran'], 100) }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table: Rekap Karyawan -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Rekap Kehadiran Karyawan - {{ $namaBulan }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>Terlambat</th>
                        <th>Persentase</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapKaryawan as $index => $karyawan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="font-weight-bold">{{ $karyawan['nama'] }}</td>
                        <td><span class="badge badge-success">{{ $karyawan['hadir'] }}</span></td>
                        <td><span class="badge badge-warning">{{ $karyawan['izin'] }}</span></td>
                        <td><span class="badge badge-danger">{{ $karyawan['alfa'] }}</span></td>
                        <td><span class="badge badge-info">{{ $karyawan['terlambat'] }}</span></td>
                        <td>
                            @if($karyawan['persentase'] >= 95)
                                <span class="badge badge-success">{{ number_format($karyawan['persentase'], 1) }}%</span>
                            @elseif($karyawan['persentase'] >= 85)
                                <span class="badge badge-warning">{{ number_format($karyawan['persentase'], 1) }}%</span>
                            @else
                                <span class="badge badge-danger">{{ number_format($karyawan['persentase'], 1) }}%</span>
                            @endif
                        </td>
                        <td>
                            @if($karyawan['persentase'] >= 95)
                                <span class="text-success">Sangat Baik</span>
                            @elseif($karyawan['persentase'] >= 85)
                                <span class="text-warning">Baik</span>
                            @else
                                <span class="text-danger">Perlu Perbaikan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($chartLabels);
    const hadirData = @json($chartHadir);
    const izinData = @json($chartIzin);
    const alfaData = @json($chartAlfa);

    const ctx = document.getElementById('reportsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Hadir',
                    data: hadirData,
                    backgroundColor: 'rgba(28, 200, 138, 0.2)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Izin',
                    data: izinData,
                    backgroundColor: 'rgba(246, 194, 62, 0.2)',
                    borderColor: 'rgba(246, 194, 62, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Alfa',
                    data: alfaData,
                    backgroundColor: 'rgba(231, 74, 59, 0.2)',
                    borderColor: 'rgba(231, 74, 59, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true
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
