@extends('layouts.app')
@section('content')

<div class="card shadow mb-4">
    <div class="card-header"><strong>Import Data Fingerprint</strong></div>
    <div class="card-body">

        <form id="formUpload" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" id="file" accept=".xls,.xlsx,.csv" required>
            <button class="btn btn-primary" type="submit">Preview</button>
        </form>

        <hr>

        <!-- PREVIEW AREA -->
        <div id="preview" style="display:none">
            <h6>Preview (Timestamp / PIN / NIP)</h6>

            <table class="table" id="tblPreview">
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>PIN</th>
                        <th>NIP</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <button id="btnProcess" class="btn btn-success">Process Import</button>
        </div>
    </div>
</div>

@endsection


@push('scripts')
<script>
$(document).ready(function () {

    // PREVIEW BUTTON
    $('#formUpload').on('submit', async function(e){
        e.preventDefault();

        let fd = new FormData(this);
        let button = $(this).find('button');
        button.text('Loading...').prop('disabled', true);

        try {
            const res = await fetch("{{ route('import.preview') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: fd
            });

            const json = await res.json();

            let tbody = $("#tblPreview tbody").empty();

            // render preview TIGA KOLOM SAJA
            json.preview.slice(1).forEach(row => {
                tbody.append(`
                    <tr>
                        <td>${row[0]}</td>
                        <td>${row[1]}</td>
                        <td>${row[2]}</td>
                    </tr>
                `);
            });

            $("#preview").show();

        } catch (err) {
            alert("Gagal melakukan preview!");
        }

        button.text('Preview').prop('disabled', false);
    });

    // PROCESS BUTTON
    $('#btnProcess').on('click', async function(){
        let button = $(this);
        button.text('Processing...').prop('disabled', true);

        try {
            const res = await fetch("{{ route('import.process') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
            });

            const json = await res.json();
            alert(json.message || 'Import selesai!');

            $("#preview").hide();
            $("#formUpload")[0].reset();

        } catch (err) {
            alert("Error saat proses import!");
        }

        button.text('Process Import').prop('disabled', false);
    });

});
</script>
@endpush