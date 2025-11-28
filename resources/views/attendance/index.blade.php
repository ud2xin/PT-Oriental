@extends('layouts.app')
@section('title', 'Data Absensi')

@section('content')

<style>
    table.table th {
        background: #f8f9fc !important;
        color: #4a4a4a !important;
        padding: 12px !important;
        font-size: 15px !important;
        font-weight: 600 !important;
        border-bottom: 1px solid #e3e6f0 !important;
        white-space: nowrap;
    }

    table.table td {
        padding: 10px !important;
        font-size: 14px !important;
        border: 1px solid #f1f1f1 !important;
        color: #4a4a4a;
        white-space: nowrap;
    }

    .col-time {
        background: #fff200 !important;
        color: #000 !important;
        white-space: nowrap;
    }
</style>

<div class="d-flex align-items-center mb-4 px-2">
    <h4 class="font-weight-bold text-primary mb-0">Data Absensi</h4>
</div>

{{-- FILTER --}}
<div class="card shadow mb-4">
    <div class="card-header bg-white">
        <h6 class="font-weight-bold text-primary mb-0">Filter Data Absensi</h6>
    </div>

    <div class="card-body">
        <form method="GET" action="{{ route('attendance.index') }}">
            <div class="row">

                <div class="col-md-3 mb-3">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>

                <div class="col-md-2 mb-3">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary btn-block">Filter</button>
                </div>

            </div>

            <div class="row mt-2">
                <div class="col-md-10">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama / NIK / Bagian / Dept..." value="{{ $search }}">
                </div>

                <div class="col-md-2 mt-2 mt-md-0">
                    <button class="btn btn-info">Cari</button>
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>

        </form>
    </div>
</div>

{{-- TABEL --}}
<div class="card shadow mb-4">
    <div class="card-header bg-white">
        <h6 class="font-weight-bold text-primary mb-0">
            {{ $tanggal ? 'Data Absensi — ' . $tanggal : 'Tidak ada data' }}
        </h6>
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
                    <td colspan="9" class="text-center text-muted py-3">Tidak ada data absensi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $absensi->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@endsection