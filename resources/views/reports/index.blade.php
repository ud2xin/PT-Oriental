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

<div class="d-flex align-items-center mb-4">
    <h4 class="font-weight-bold mb-0 text-primary">Daily Absen Report</h4>
</div>

{{-- FILTER --}}
<div class="card mb-4 shadow">
    <div class="card-header bg-white">
        <h6 class="font-weight-bold text-primary mb-0">Filter Laporan</h6>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('reports.index') }}">
            <div class="row">
                <div class="col-md-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary btn-block">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- DAILY ABSEN --}}
<div class="card shadow mb-4">
    <div class="card-header bg-white">
        <h6 class="font-weight-bold text-primary mb-0">Daily Absen Report</h6>
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover">
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
                    <td class="text-primary fw-bold">{{ $d->OVER_CONV }}</td>
                    <td>{{ $d->alas_name ?? '' }}</td>
                    <td>{{ $d->remk_name ?? '' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-3 text-muted">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION FIX (TIDAK RESET TANGGAL) --}}
        <div class="d-flex justify-content-between mt-3">

            <div>
                Menampilkan
                {{ $daily->firstItem() ?? 0 }} -
                {{ $daily->lastItem() ?? 0 }}
                dari {{ $daily->total() }} entri
            </div>

            <div>
                {{ $daily->appends(['tanggal' => $tanggal])->links('pagination::bootstrap-5') }}
            </div>

        </div>

    </div>
</div>

@endsection