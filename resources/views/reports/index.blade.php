@extends('layouts.app')
@section('title', 'Laporan Kehadiran')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Laporan Kehadiran</h1>
</div>

<!-- Filter Section -->
<!-- Filter Section -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row">

                <!-- Tanggal Awal -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                </div>

                <!-- Tanggal Akhir -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>

                {{-- <!-- Departemen -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Departemen</label>
                    <select name="departemen" class="form-control">
                        @foreach($departemenList as $key => $value)
                        <option value="{{ $key }}" {{ request('departemen', 'all') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div> --}}

                <!-- Tombol Filter -->
                <div class="col-md-2 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-filter mr-1"></i> Filter
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
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Karyawan</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_karyawan'] }}</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Hadir</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_hadir'] }}</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Izin</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_izin'] }}</div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Alfa</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $summary['total_alfa'] }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Table: Rekap Per Karyawan -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Rekap Kehadiran Per Karyawan
            ({{ $tanggalAwal }} s/d {{ $tanggalAkhir }})
        </h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th> <!-- DITAMBAHKAN -->
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Bagian</th>
                        <th>Dept</th>
                        <th>Group</th>
                        <th>IN</th>
                        <th>OUT</th>
                        <th>Status</th>
                        <th>Over Time</th>
                        <th>Alasan</th>
                        <th>Remark</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($absensi as $i => $a)
                    <tr>
                        <td>{{ $absensi->firstItem() + $i }}</td>
                        <td>{{ $a->tanggal }}</td>
                        <td>{{ $a->nip }}</td>
                        <td>{{ $a->nama }}</td>
                        <td>{{ $a->jabatan }}</td>
                        <td>{{ $a->departemen }}</td>
                        <td>-</td>
                        <td>{{ $a->jam_masuk ?? '-' }}</td>
                        <td>{{ $a->jam_keluar ?? '-' }}</td>
                        <td>{{ $a->jam_masuk ? 'Hadir' : 'Alfa' }}</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-between mt-3">
                <div>
                    Menampilkan {{ $absensi->firstItem() }} - {{ $absensi->lastItem() }} dari {{ $absensi->total() }} entri
                </div>
                <div>
                    {{ $absensi->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
