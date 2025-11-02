@extends('layouts.app')
@section('content')
<div class="card shadow"><div class="card-body">
    <table class="table" id="attendanceTable">
        <thead><tr><th>#</th><th>NIP</th><th>Nama</th><th>Tanggal</th><th>Masuk</th><th>Pulang</th><th>Status</th></tr></thead>
    </table>
</div></div>
@endsection

@push('scripts')
<script>
$(function(){
    $('#attendanceTable').DataTable({
        processing:true,
        serverSide:true,
        ajax: "{{ route('attendance.index') }}",
        columns: [
        { data: 'DT_RowIndex', orderable:false, searchable:false },
        { data: 'nip' }, { data: 'nama' }, { data: 'tanggal' },
        { data: 'jam_masuk' }, { data: 'jam_pulang' }, { data: 'status_kehadiran' }
        ]
    });
});
</script>
@endpush
