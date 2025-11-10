@extends('layouts.app')
@section('title', 'Laporan Kehadiran Pribadi')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Kehadiran Pribadi</h1>
    <a href="{{ route('reports.export') }}" class="btn btn-success">
        <i class="fas fa-download mr-2"></i>Download PDF
    </a>
</div>

<!-- Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Pilih Periode</h6>
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
                        <i class="fas fa-search mr-1"></i>Lihat
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Rekap Card -->
<div class="card shadow mb-4 border-left-primary">
    <div class="card-header py-3 bg-primary text-white">
        <h5 class="m-0 font-weight-bold">Rekap Kehadiran - {{ $rekapPribadi['bulan'] }}</h5>
    </div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-2 mb-3">
                <div class="border rounded p-3 h-100">
                    <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                    <h3 class="font-weight-bold">{{ $rekapPribadi['total_hari_kerja'] }}</h3>
                    <small class="text-muted">Hari Kerja</small>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="border rounded p-3 h-100 bg-success text-white">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h3 class="font-weight-bold">{{ $rekapPribadi['hadir'] }}</h3>
                    <small>Hadir</small>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="border rounded p-3 h-100 bg-warning text-white">
                    <i class="fas fa-file-alt fa-2x mb-2"></i>
                    <h3 class="font-weight-bold">{{ $rekapPribadi['izin'] }}</h3>
                    <small>Izin</small>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="border rounded p-3 h-100 bg-danger text-white">
                    <i class="fas fa-times-circle fa-2x mb-2"></i>
                    <h3 class="font-weight-bold">{{ $rekapPribadi['alfa'] }}</h3>
                    <small>Alfa</small>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="border rounded p-3 h-100 bg-info text-white">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h3 class="font-weight-bold">{{ $rekapPribadi['terlambat'] }}</h3>
                    <small>Terlambat</small>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="border rounded p-3 h-100">
                    <i class="fas fa-percentage fa-2x text-primary mb-2"></i>
                    <h3 class="font-weight-bold">{{ number_format($rekapPribadi['persentase_kehadiran'], 1) }}%</h3>
                    <small class="text-muted">Persentase</small>
                </div>
            </div>
        </div>

        <hr>

        <!-- Progress Bar -->
        <h5 class="mb-3">Evaluasi Kehadiran</h5>
        <div class="row">
            <div class="col-md-8">
                <h4 class="small font-weight-bold">
                    Persentase Kehadiran
                    <span class="float-right">{{ number_format($rekapPribadi['persentase_kehadiran'], 1) }}%</span>
                </h4>
                <div class="progress mb-3" style="height: 25px;">
                    @php
                        $persentase = $rekapPribadi['persentase_kehadiran'];
                        $progressClass = $persentase >= 95 ? 'bg-success' : ($persentase >= 85 ? 'bg-warning' : 'bg-danger');
                    @endphp
                    <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $persentase }}%">
                        {{ number_format($persentase, 1) }}%
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                @if($persentase >= 95)
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-trophy fa-2x mb-2"></i>
                        <p class="mb-0 font-weight-bold">Sangat Baik!</p>
                        <small>Pertahankan!</small>
                    </div>
                @elseif($persentase >= 85)
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-thumbs-up fa-2x mb-2"></i>
                        <p class="mb-0 font-weight-bold">Baik</p>
                        <small>Tingkatkan lagi!</small>
                    </div>
                @else
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                        <p class="mb-0 font-weight-bold">Perlu Perbaikan</p>
                        <small>Lebih disiplin ya!</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Detail Harian Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Detail Kehadiran Harian - {{ $rekapPribadi['bulan'] }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detailHarian as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail['tanggal'])->format('d/m/Y') }}</td>
                        <td>{{ $detail['hari'] }}</td>
                        <td>
                            @if($detail['jam_masuk'] !== '-')
                                <span class="text-dark">{{ $detail['jam_masuk'] }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($detail['jam_keluar'] !== '-')
                                <span class="text-dark">{{ $detail['jam_keluar'] }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($detail['status'] === 'Hadir')
                                <span class="badge badge-success">
                                    <i class="fas fa-check mr-1"></i>{{ $detail['status'] }}
                                </span>
                            @elseif($detail['status'] === 'Izin')
                                <span class="badge badge-warning">
                                    <i class="fas fa-file-alt mr-1"></i>{{ $detail['status'] }}
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times mr-1"></i>{{ $detail['status'] }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Info Catatan -->
<div class="alert alert-info">
    <i class="fas fa-info-circle mr-2"></i>
    <strong>Catatan:</strong> Jika ada ketidaksesuaian data, silakan hubungi bagian HRD atau Admin departemen Anda.
</div>
@endsection
