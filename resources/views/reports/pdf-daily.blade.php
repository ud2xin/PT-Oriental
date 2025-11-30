<!DOCTYPE html>
<html>
<head>
    <title>Laporan Harian - {{ $tanggal }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2, .header p { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Daily Absen Report</h2>
        <p>Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Bagian</th>
                <th>Dept</th>
                <th>IN</th>
                <th>OUT</th>
                <th>Status</th>
                <th>Overtime</th>
                <th>Remark</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $d)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    
                    {{-- PERBAIKAN: Menggunakan nama kolom yang sesuai dengan database Anda --}}
                    <td>{{ $d->empl_nmbr ?? '-' }}</td>
                    <td>{{ $d->korx_name ?? '-' }}</td>
                    <td>{{ $d->divx_name ?? '-' }}</td>
                    <td>{{ $d->dept_name ?? '-' }}</td>
                    <td class="text-center">{{ $d->COME_TIME ?? '-' }}</td>
                    <td class="text-center">{{ $d->LEAV_TIME ?? '-' }}</td>
                    <td>{{ $d->stat_name ?? '-' }}</td>
                    <td class="text-center">{{ $d->OVER_CONV ?? '-' }}</td>
                    <td>{{ $d->remk_name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data absensi untuk tanggal ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
