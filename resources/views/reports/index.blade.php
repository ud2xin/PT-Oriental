@extends('layouts.app')
@section('title', 'Daily Absen Report')

@section('content')

<style>
    table.table th {
        font-weight: 700 !important;
        background: #f5f6f7;
        color: #4a4a4a !important;
        white-space: nowrap;
        padding: 10px !important;
    }

    table.table td {
        padding: 10px !important;
        font-size: 14px;
        white-space: nowrap;
    }
</style>

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="font-weight-bold mb-0 text-primary">Daily Absen Report</h4>
</div>

{{-- FILTER SECTION --}}
<div class="card mb-4 shadow">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row align-items-end">
                
                {{-- INPUT TANGGAL --}}
                <div class="col-md-3 mb-2">
                    <label class="font-weight-bold">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>

                {{-- INPUT DEPARTEMEN --}}
                <div class="col-md-3 mb-2">
                    <label class="font-weight-bold">Departemen</label>
                    <select name="department_id" class="form-control">
                        <option value="">Semua Departemen</option>
                        {{-- Menggunakan ?? [] agar tidak error jika variabel tidak dikirim controller --}}
                        @foreach ($departemenList ?? [] as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- TOMBOL ACTION (FILTER & EXPORT) --}}
                <div class="col-md-6 mb-2">
                    <div class="d-flex">
                        {{-- Tombol Filter --}}
                        <button type="submit" class="btn btn-primary mr-2">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>

                        {{-- Tombol Export PDF --}}
                        {{-- Menggunakan formaction agar tanggal yang dipilih ikut terkirim --}}
                        <button type="submit" 
                                formaction="{{ route('reports.export') }}" 
                                formtarget="_blank" 
                                class="btn btn-danger">
                            <i class="fas fa-file-pdf mr-1"></i> Export PDF
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

{{-- TABEL DATA --}}
<div class="card shadow mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-primary">Data Kehadiran</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Bagian</th>
                        <th>Dept</th>
                        <th>Group</th>
                        <th>IN</th>
                        <th>OUT</th>
                        <th>Status</th>
                        <th>Overtime</th>
                        <th>Alasan</th>
                        <th>Remark</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($daily as $d)
                    <tr>
                        <td>{{ $d->empl_nmbr }}</td>
                        <td>{{ $d->korx_name }}</td>
                        <td>{{ $d->divx_name ?? '-' }}</td>
                        <td>{{ $d->dept_name }}</td>
                        <td>{{ $d->grop_name ?? '-' }}</td>
                        <td>{{ $d->COME_TIME }}</td>
                        <td>{{ $d->LEAV_TIME }}</td>
                        <td>{{ $d->stat_name }}</td>
                        <td class="text-primary font-weight-bold">{{ $d->OVER_CONV }}</td>
                        <td>{{ $d->alas_name ?? '' }}</td>
                        <td>{{ $d->remk_name ?? '' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-4 text-muted">
                            <i class="fas fa-folder-open mb-2 d-block fa-2x"></i>
                            Tidak ada data absensi untuk tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted small">
                Menampilkan {{ $daily->firstItem() ?? 0 }} s/d {{ $daily->lastItem() ?? 0 }} dari {{ $daily->total() }} data
            </div>
            <div>
                {{ $daily->appends(request()->all())->links('pagination::bootstrap-4') }}
            </div>
        </div>

    </div>
</div>

@endsection