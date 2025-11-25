@extends('layouts.app')
@section('title', 'Data Absensi')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Absensi</h1>
    {{-- <div>
        <a href="{{ route('attendance.export') }}" class="btn btn-success">
            <i class="fas fa-download mr-2"></i>Export Excel
        </a>
        <a href="{{ route('attendance.checkin.show') }}" class="btn btn-primary">
            <i class="fas fa-fingerprint mr-2"></i>Absen Sekarang
        </a>
    </div> --}}
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<!-- Filter & Search -->
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
                        @for($m = 1; $m <= 12; $m++) <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->translatedFormat('F') }}
                            </option>
                            @endfor
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control">
                        @for($y = date('Y'); $y >= date('Y')-3; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                {{-- @if($role === 'super_admin')
                <div class="col-md-3 mb-3">
                    <label for="departemen" class="form-label">Departemen</label>
                    <select name="departemen" id="departemen" class="form-control">
                        <option value="">-- Semua --</option>
                        @foreach($departemenList as $dep)
                        <option value="{{ $dep }}" {{ $departemen == $dep ? 'selected' : '' }}>{{ $dep }}</option>
                        @endforeach
                    </select>
                </div>
                @endif --}}

                {{-- <div class="col-md-2 mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="Hadir" {{ $status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Izin" {{ $status == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Alfa" {{ $status == 'Alfa' ? 'selected' : '' }}>Alfa</option>
                    </select>
                </div> --}}

                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-10 mb-2">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama / NIP / PIN / Jabatan / Departemen..." value="{{ $search }}">
                </div>
                <div class="col-md-2 mb-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-search mr-1"></i> Cari
                        </button>
                        <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <!-- (kamu bisa tetap menampilkan card-card seperti sebelumnya) -->
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $attendanceData->total() }}</div>
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
                        <th>Tanggal Scan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>PIN</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Departemen</th>
                        <th>Kantor</th>
                        <th>Verifikasi</th>
                        <th>I/O</th>
                        <th>Workcode</th>
                        <th>SN</th>
                        <th>Mesin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendanceData as $attendance)
                    <tr>
                        <td>{{ $attendance->tanggal_scan ? \Carbon\Carbon::parse($attendance->tanggal_scan)->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td>{{ $attendance->tanggal ? \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') : '-' }}
                        </td>
                        <td>{{ $attendance->jam ? \Carbon\Carbon::parse($attendance->jam)->format('H:i:s') : '-' }}</td>
                        <td>{{ $attendance->pin ?? '-' }}</td>
                        <td>{{ $attendance->nip ?? '-' }}</td>
                        <td>{{ $attendance->nama ?? '-' }}</td>
                        <td>{{ $attendance->jabatan ?? '-' }}</td>
                        <td>{{ $attendance->departemen ?? '-' }}</td>
                        <td>{{ $attendance->kantor ?? '-' }}</td>
                        <td>{{ $attendance->verifikasi ?? '-' }}</td>
                        <td>
                            @if($attendance->io == 1)
                            <span class="badge bg-success">Masuk</span>
                            @elseif($attendance->io == 2)
                            <span class="badge bg-danger">Keluar</span>
                            @else
                            <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td>{{ $attendance->workcode ?? '-' }}</td>
                        <td>{{ $attendance->sn ?? '-' }}</td>
                        <td>{{ $attendance->mesin ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            Tidak ada data absensi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>Menampilkan {{ $attendanceData->firstItem() ?? 0 }} - {{ $attendanceData->lastItem() ?? 0 }} dari
                {{ $attendanceData->total() }} entri
            </div>
            <div>{{ $attendanceData->links() }}</div>
        </div>
    </div>
</div>
@endsection
