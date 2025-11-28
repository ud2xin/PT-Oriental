@extends('layouts.app')

@section('content')

<style>
.ot-wrapper {
    overflow-x: auto;
    padding-bottom: 20px;
}

.ot-table {
    min-width: 3300px;
    font-size: 14px;
}

.ot-table th {
    white-space: nowrap;
    text-align: center;
    padding: 10px 8px;
    font-weight: 600;
    background: #f8f9fc;
    border: 1px solid #dcdcdc !important;
}

.ot-table td {
    white-space: nowrap;
    padding: 8px 6px !important;
    border: 1px solid #eee !important;
    font-size: 13px;
    text-align: center;
    font-weight: normal !important;
}

.ot-name {
    text-align: left !important;
    padding-left: 10px !important;
}

.ot-total {
    font-weight: 700 !important;
    color: #007bff !important;
}

.bg-blue {
    background-color: #b3ecff !important;
}

.bg-pink {
    background-color: #ffb3d9 !important;
}

.bg-purple {
    background-color: #e6ccff !important;
}

.pagination-container {
    display: flex;
    justify-content: flex-end !important;
    margin-top: 10px;
}
</style>

<div class="container-fluid">

    <h3 class="text-primary font-weight-bold mb-4">Laporan Overtime</h3>

    {{-- FILTER --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('reports.overtime.filter') }}">
                @csrf

                <label class="font-weight-bold">Tanggal</label>
                <div class="d-flex">
                    <input type="date" name="tanggal" class="form-control w-25" required>
                    <button class="btn btn-primary ml-3">Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- TABEL HANYA MUNCUL SETELAH FILTER --}}
    @if($tanggal && $data)

    <div class="card shadow mb-4">

        <div class="card-header bg-white">
            <h5 class="text-primary font-weight-bold mb-0">
                Overtime Report — {{ $tanggal }}
            </h5>
        </div>

        <div class="ot-wrapper">
            <table class="table table-bordered ot-table">
                <thead style="position: sticky; top: 0; z-index: 10;">
                    <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Dept</th>
                        <th>Total OT</th>

                        @php
                        $order = [21,22,23,24,25,26,27,28,29,30,31,
                        1,2,3,4,5,6,7,8,9,10,
                        11,12,13,14,15,16,17,18,19,20];
                        @endphp

                        @foreach($order as $d)
                        <th>{{ sprintf('%02d', $d) }}</th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $row)
                    <tr>
                        <td>{{ $row['nik'] }}</td>
                        <td class="ot-name">{{ $row['nama'] }}</td>
                        <td>{{ $row['dept'] }}</td>
                        <td class="ot-total">{{ $row['total_ot'] }}</td>

                        @foreach($order as $d)
                        @php
                        $value = $row[$d];
                        $bg = "";

                        if (in_array($d, [24,31,7,14])) $bg = "bg-pink";
                        if (in_array($d, [23,30,6,13,20])) $bg = "bg-blue";
                        if ($d == 5) $bg = "bg-pink";
                        @endphp
                        <td class="{{ $bg }}">{{ $value }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="pagination-container">
            {{ $data->links() }}
        </div>

    </div>

    @endif

</div>
@endsection