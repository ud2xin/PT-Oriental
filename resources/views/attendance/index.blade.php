@extends('layouts.app')
@section('title', 'Data Absensi')

@section('content')
<style>
    /* Kolom time kuning */
    .col-time {
        background: #fff200 !important;
        padding: 6px !important;
        color: #000 !important;
        white-space: nowrap;
    }

    /* Membesarkan tabel */
    table.table td, table.table th {
        padding: 10px !important;
        font-size: 14px !important;
    }

    table.table th {
        background: #f5f6f7;
        font-weight: normal !important; /* TANPA BOLD */
    }
</style>

<h1 class="h4 mb-4">Data Absensi</h1>

<!-- FILTER -->
>>>>>>> 2c1747a (selesai import data absen dan daily)
<div class="card shadow mb-4">
    <div class="card-header">
        <strong>Filter Data Absensi</strong>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('attendance.index') }}">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Bulan</label>
                    <select name="bulan" class="form-control">
                        @for($m=1;$m<=12;$m++)
                            <option value="{{ $m }}" {{ $bulan==$m?'selected':'' }}>
                                {{ \Carbon\Carbon::create(null,$m,1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label>Tahun</label>
                    <select name="tahun" class="form-control">
                        @for($y= date('Y'); $y>=date('Y')-4; $y--)
                            <option value="{{ $y }}" {{ $tahun==$y?'selected':'' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary btn-block">Filter</button>
                </div>

            </div>
            <div class="row mt-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama / NIK / Bagian / Dept / Group..."
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-2 mt-2 mt-md-0">
                    <button class="btn btn-info">Cari</button>
                    <a href="{{ route('attendance.index', ['bulan'=>$bulan,'tahun'=>$tahun]) }}" 
                        class="btn btn-secondary">Reset</a>

                </div>
            </div>

        </form>
    </div>
</div>

<!-- TABEL DATA ABSENSI -->
>>>>>>> 2c1747a (selesai import data absen dan daily)
<div class="card shadow mb-4">
    <div class="card-header">
        <strong>
            Data Absensi - {{ \Carbon\Carbon::create(null,$bulan,1)->translatedFormat('F') }} {{ $tahun }}
        </strong>
    </div>

    <div class="card-body">
        <div class="table-responsive">

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Bagian</th>
                        <th>Dept</th>
                        <th>Group</th>
                        <th>Type</th>
                        <th>Time</th>
                        <th>Terminal</th>
                        <th>Edit By</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($absensi as $a)
                    <tr>
                        <td>{{ $a->EMPL_NMBR }}</td>
                        <td>{{ $a->KORX_NAME }}</td>
                        <td>{{ $a->DIVX_NAME }}</td>
                        <td>{{ $a->DEPT_NAME }}</td>
                        <td>{{ $a->GROUP_NAME }}</td>
                        <td>{{ $a->TYPE_CODE }}</td>

                        <td class="col-time">{{ $a->TRNS_DATE }}</td>

                        <td>{{ $a->TERM_NMBR }}</td>
                        <td>{{ $a->TRAN_USR1 }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Tidak ada data absensi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-end">
                {{ $absensi->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

>>>>>>> 2c1747a (selesai import data absen dan daily)
@endsection
