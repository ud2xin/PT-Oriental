@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4"><div class="card-body">
        <h5>Ringkasan Kehadiran Bulan Ini</h5>
        <canvas id="attendanceChart"></canvas>
        </div></div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow mb-4"><div class="card-body">
        <h5>Statistik Lain</h5>
        <p>Konten...</p>
        </div></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const labels = @json($chartLabels ?? ['Minggu 1','Minggu 2']);
const hadir = @json($chartHadir ?? [10,20]);
const telat = @json($chartTelat ?? [2,1]);

const ctx = document.getElementById('attendanceChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [
        { label:'Hadir', data: hadir },
        { label:'Telat', data: telat }
        ]
    },
    options: { responsive:true }
});
</script>
@endpush
