@extends('layouts.app')
@section('title', 'Laporan Kehadiran')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Kehadiran</h1>
    <a href="{{ route('reports.export') }}" class="btn btn-success">
        <i class="fas fa-download mr-2"></i>Export Excel
    </a>
</div>

<!-- Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select name="bulan" id="bulan" class="form-control">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control">
                        @for($y = date('Y'); $y >= date('Y')-3; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="departemen" class="form-label">Departemen</label>
                    <select name="departemen" id="departemen" class="form-control">
                        @foreach($departemenList as $key => $value)
                            <option value="{{ $key }}" {{ $departemen == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
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
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Karyawan</div>
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

<!-- Chart Section -->
<div class="row mb-4">
    <div class="col-xl-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Kehadiran per Minggu - {{ $namaBulan }}</h6>
            </div>
            <div class="card-body">
                <canvas id="reportsChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Ringkasan Bulan Ini</h6>
            </div>
            <div class="card-body">
                <h4 class="small font-weight-bold">
                    Rata-rata Kehadiran
                    <span class="float-right">{{ number_format($summary['rata_rata_kehadiran'], 1) }}%</span>
                </h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $summary['rata_rata_kehadiran'] }}%"></div>
                </div>

                <div class="mb-3">
                    <i class="fas fa-calendar-check text-success mr-2"></i>
                    <strong>Total Hari Kerja:</strong> {{ $summary['total_hari_kerja'] }} hari
                </div>
                <div class="mb-3">
                    <i class="fas fa-clock text-warning mr-2"></i>
                    <strong>Total Terlambat:</strong> {{ $summary['total_terlambat'] }} kali
                </div>
                <hr>
                <div class="text-center">
                    <i class="fas fa-info-circle text-info"></i>
                    <small class="text-muted">Data periode {{ $namaBulan }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table: Rekap Per Karyawan -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Rekap Kehadiran per Karyawan - {{ $namaBulan }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Departemen</th>
                        <th>Hadir</th>
                        <th>Izin</th>
                        <th>Alfa</th>
                        <th>Terlambat</th>
                        <th>Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapKaryawan as $index => $karyawan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="font-weight-bold">{{ $karyawan['nama'] }}</td>
                        <td>{{ $karyawan['departemen'] }}</td>
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
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Hadir',
                    data: hadirData,
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Izin',
                    data: izinData,
                    backgroundColor: 'rgba(246, 194, 62, 0.8)',
                    borderColor: 'rgba(246, 194, 62, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Alfa',
                    data: alfaData,
                    backgroundColor: 'rgba(231, 74, 59, 0.8)',
                    borderColor: 'rgba(231, 74, 59, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    stacked: false
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
