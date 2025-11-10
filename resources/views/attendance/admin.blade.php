@extends('layouts.app')
@section('title', 'Data Absensi Departemen')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Absensi - Departemen {{ $userDepartemen }}</h1>
    <div>
        <a href="{{ route('attendance.export') }}" class="btn btn-success">
            <i class="fas fa-download mr-2"></i>Export Excel
        </a>
        <a href="{{ route('attendance.checkin') }}" class="btn btn-primary">
            <i class="fas fa-fingerprint mr-2"></i>Absen Sekarang
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Data Absensi</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('attendance.index') }}">
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
                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="hadir" {{ $status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ $status == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="alfa" {{ $status == 'alfa' ? 'selected' : '' }}>Alfa</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama karyawan..." value="{{ $search }}">
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-info btn-block">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Hadir</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">4</div>
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
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Izin</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">1</div>
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
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Alfa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($attendanceData) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Absensi - {{ $namaBulan }}</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceData as $index => $attendance)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="font-weight-bold">{{ $attendance['nama'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance['tanggal'])->format('d/m/Y') }}</td>
                        <td>
                            @if($attendance['jam_masuk'] !== '-')
                                <span class="text-dark">{{ $attendance['jam_masuk'] }}</span>
                                @if(strtotime($attendance['jam_masuk']) > strtotime('08:00'))
                                    <span class="badge badge-warning badge-sm ml-1">Telat</span>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance['jam_keluar'] !== '-')
                                <span class="text-dark">{{ $attendance['jam_keluar'] }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($attendance['status'] === 'Hadir')
                                <span class="badge badge-success">
                                    <i class="fas fa-check mr-1"></i>{{ $attendance['status'] }}
                                </span>
                            @elseif($attendance['status'] === 'Izin')
                                <span class="badge badge-warning">
                                    <i class="fas fa-file-alt mr-1"></i>{{ $attendance['status'] }}
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="fas fa-times mr-1"></i>{{ $attendance['status'] }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $attendance['keterangan'] }}</td>
                        <td>
                            <a href="{{ route('attendance.show', $attendance['id']) }}" class="btn btn-sm btn-info" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            Tidak ada data absensi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Info Alert -->
<div class="alert alert-info">
    <i class="fas fa-info-circle mr-2"></i>
    <strong>Informasi:</strong> Data yang ditampilkan hanya untuk karyawan di departemen <strong>{{ $userDepartemen }}</strong>.
</div>
@endsection
