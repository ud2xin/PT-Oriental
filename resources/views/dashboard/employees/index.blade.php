@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Karyawan Realtime</h1>

    <div class="table-responsive" style="overflow-x:auto;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>KTP</th>
                    <th>NPWP</th>
                    <th>Rekening</th>
                    <th>Nama</th>
                    {{-- <th>PIN</th> --}}
                    <th>L/P</th>
                    <th>Status</th>
                    <th>Join</th>
                    <th>Resign</th>
                    <th>Jabatan</th>
                    <th>Departemen</th>
                    <th>Lahir</th>
                    <th>Tanggal</th>
                    <th>PTKP</th>
                    <th>Telepon</th>
                    <th>Pendidikan</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->empl_nmbr }}</td>
                        <td>{{ $employee->JUMN_NMBR }}</td>
                        <td>{{ $employee->BIBI_NMBR }}</td>
                        <td>{{ $employee->HTEL_NMBR }}</td>
                        <td>{{ $employee->korx_name }}</td>
                        <td>{{ $employee->SEXX_PART }}</td>
                        <td>{{ $employee->JJIK_PART }}</td>
                        <td>{{ $employee->IPSA_DATE}}</td>
                        <td>{{ $employee->TESA_DATE }}</td>
                        <td>{{ $employee->JABATAN}}</td>
                        <td>{{ $employee->dept_name }}</td>
                        <td>{{ $employee->BDAY_PART }}</td>
                        <td>{{ $employee->BDAY_DATE }}</td>
                        <td>{{ $employee->MARY_PART }}</td>
                        <td>{{ $employee->TELX_NMBR }}</td>
                        <td>{{ $employee->IPSA_PART }}</td>
                        <td>{{ $employee->CURR_ADR1}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            Menampilkan {{ $employees->firstItem() }} - {{ $employees->lastItem() }} dari {{ $employees->total() }} entri
        </div>
        <div class="mt-3">
            {{ $employees->links() }}
        </div>

    </div>
</div>
@endsection
