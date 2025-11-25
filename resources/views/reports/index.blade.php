@extends('layouts.app')
@section('title', 'Daily Absen Report')

@section('content')

<h1 class="h3 mb-4 text-gray-800">Daily Absen Report</h1>

{{-- FILTER --}}
<div class="card mb-4 shadow">
    <div class="card-header">
        <strong>Filter Laporan</strong>
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
    <div class="card-header bg-success text-white">
        <strong>Daily Absen Report</strong>
    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover">
            <thead class="bg-light">
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
                    <td colspan="12" class="text-center">Tidak ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-end mt-3">
            {{ $daily->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

@endsection
