@extends('layouts.app')
@section('title', 'Data Karyawan Realtime')

@section('content')
<div class="d-flex align-items-center mb-4 px-2">
    <h4 class="font-weight-bold mb-0 text-primary">Data Karyawan</h4>
</div>

<!-- Filter Form -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Filter Data Karyawan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}">
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label">L/P</label>
                    <select name="gender" class="form-control">
                        <option value="">-- Semua --</option>
                        <option value="L" {{ request('gender')=='L'?'selected':'' }}>Laki-Laki</option>
                        <option value="P" {{ request('gender')=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="">-- Semua --</option>
                        <option value="ACTIVE" {{ request('status')=='ACTIVE'?'selected':'' }}>ACTIVE</option>
                        <option value="RESIGN" {{ request('status')=='RESIGN'?'selected':'' }}>RESIGN</option>
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Jabatan</label>
                    <select name="jabatan" class="form-control">
                        <option value="">-- Semua --</option>
                        @foreach($jabatanList as $j)
                        <option value="{{ $j }}" {{ request('jabatan')==$j?'selected':'' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Departemen</label>
                    <select name="departemen" class="form-control">
                        <option value="">-- Semua --</option>
                        @foreach($departemenList as $d)
                        <option value="{{ $d }}" {{ request('departemen')==$d?'selected':'' }}>{{ $d }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">PTKP</label>
                    <select name="ptkp" class="form-control">
                        <option value="">-- Semua --</option>
                        @foreach($ptkpList as $p)
                        <option value="{{ $p }}" {{ request('ptkp')==$p?'selected':'' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Pendidikan</label>
                    <select name="pendidikan" class="form-control">
                        <option value="">-- Semua --</option>
                        @foreach($pendidikanList as $pdd)
                        <option value="{{ $pdd }}" {{ request('pendidikan')==$pdd?'selected':'' }}>{{ $pdd }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-10 mb-2">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari nama / NIP / Jabatan / Departemen..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 mb-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary ml-2">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- DATA TABLE -->
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>KTP</th>
                        <th>NPWP</th>
                        <th>Rekening</th>
                        <th>Nama</th>
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
                    @foreach($employees as $employee)
                    <tr>
                        <td>{{ $employee->empl_nmbr }}</td>
                        <td>{{ $employee->JUMN_NMBR }}</td>
                        <td>{{ $employee->BIBI_NMBR }}</td>
                        <td>{{ $employee->HTEL_NMBR }}</td>
                        <td>{{ $employee->korx_name }}</td>
                        <td>{{ $employee->SEXX_PART }}</td>
                        <td>{{ $employee->JJIK_PART }}</td>
                        <td>{{ $employee->IPSA_DATE }}</td>
                        <td>{{ $employee->TESA_DATE }}</td>
                        <td>{{ $employee->JABATAN }}</td>
                        <td>{{ $employee->dept_name }}</td>
                        <td>{{ $employee->BDAY_PART }}</td>
                        <td>{{ $employee->BDAY_DATE }}</td>
                        <td>{{ $employee->MARY_PART }}</td>
                        <td>{{ $employee->TELX_NMBR }}</td>
                        <td>{{ $employee->IPSA_PART }}</td>
                        <td>{{ $employee->CURR_ADR1 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between mt-3">
            <div>
                Menampilkan {{ $employees->firstItem() }} - {{ $employees->lastItem() }} dari {{ $employees->total() }}
                entri
            </div>
            <div>{{ $employees->links() }}</div>
        </div>
    </div>
</div>
@endsection