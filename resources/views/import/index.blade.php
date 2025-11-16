@extends('layouts.app')
@section('content')
<div class="card shadow mb-4">
    <div class="card-header"><strong>Import Data Fingerprint</strong></div>
    <div class="card-body">
        <form id="formUpload" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="file" accept=".xls,.xlsx" required>
        <button class="btn btn-primary" type="submit">Preview</button>
        </form>
        <hr>
        <div id="preview" style="display:none">
        <h6>Preview</h6>
        <table class="table" id="tblPreview"><thead><tr><th>#</th><th>Code</th><th>Timestamp</th></tr></thead><tbody></tbody></table>
        <button id="btnProcess" class="btn btn-success">Process Import</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Tunggu hingga seluruh halaman (DOM) siap
    $(document).ready(function() {
        
        // 2. LOG: Cek apakah script ini berjalan
        console.log('Script import.index.blade.php berhasil dimuat dan DOM siap.');

        // 3. Targetkan form
        $('#formUpload').on('submit', async function(e){
            // 4. Hentikan submit standar
            e.preventDefault();
            
            // 5. LOG: Cek apakah event listener ini berjalan
            console.log('Form submit "Preview" terdeteksi, e.preventDefault() dijalankan.');

            const fd = new FormData(this);
            const button = $(this).find('button');

            // Disable tombol
            button.text('Loading...').prop('disabled', true);

            try {
                console.log('Mengirim request fetch ke {{ route("import.preview") }}...');
                const res = await fetch("{{ route('import.preview') }}", { 
                    method:'POST', 
                    headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}, 
                    body:fd 
                });

                if (!res.ok) {
                    console.error('Fetch gagal! Status:', res.status);
                    throw new Error(`HTTP error! status: ${res.status}`);
                }

                const json = await res.json();
                console.log('Fetch berhasil, data JSON diterima:', json);

                // Render preview
                let tbody = $('#tblPreview tbody').empty();
                
                // (Ini versi yang lebih baik dari respons saya sebelumnya)
                let thead = $('#tblPreview thead tr').empty();
                if (json.preview.length > 0) {
                    // Gunakan baris pertama (header) untuk judul kolom
                    json.preview[0].forEach(header => thead.append(`<th>${header}</th>`));

                    // Tampilkan sisa baris (data)
                    json.preview.slice(1).forEach((row, i) => {
                        let tr = $('<tr></tr>');
                        row.forEach(cell => tr.append(`<td>${cell || ''}</td>`)); // Tambahkan '|| ""' untuk sel kosong
                        tbody.append(tr);
                    });
                }
                
                $('#preview').show();

            } catch (e) {
                console.error('Terjadi error di blok try-catch "Preview":', e);
                alert('Terjadi error saat preview. Cek console F12 untuk detail.');
            } finally {
                // Kembalikan tombol ke semula
                button.text('Preview').prop('disabled', false);
            }
        });

        // Event listener untuk tombol Process
        $('#btnProcess').on('click', async function(){
            console.log('Tombol "Process Import" diklik.');
            
            const button = $(this);
            button.text('Processing...').prop('disabled', true);

            try {
                console.log('Mengirim request fetch ke {{ route("import.process") }}...');
                const res = await fetch("{{ route('import.process') }}", { 
                    method:'POST', 
                    headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}
                });

                if (!res.ok) {
                    console.error('Fetch "process" gagal! Status:', res.status);
                    throw new Error(`HTTP error! status: ${res.status}`);
                }

                const json = await res.json();
                console.log('Fetch "process" berhasil:', json);
                
                alert(json.message || 'Done');
                
                $('#preview').hide();
                $('#formUpload')[0].reset();
                $('#tblPreview tbody').empty();
                $('#tblPreview thead tr').empty().html('<th>#</th><th>Code</th><th>Timestamp</th>');

            } catch (e) {
                console.error('Terjadi error di blok try-catch "Process":', e);
                alert('Terjadi error saat proses import. Cek console F12 untuk detail.');
            } finally {
                button.text('Process Import').prop('disabled', false);
            }
        });

    });
</script>
@endpush
