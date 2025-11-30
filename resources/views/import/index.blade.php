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
        <div id="preview" style="display:none;">
            <h6 class="mb-3">Preview Data</h6>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="previewTable" width="100%">
                    <thead id="previewHead"></thead>
                    <tbody id="previewBody"></tbody>
                </table>
            </div>

            <button id="btnProcess" class="btn btn-success mt-3">Process Import</button>
        </div>

    </div>

</div>
@endsection


@push('scripts')

{{-- DATATABLES --}}
<link rel="stylesheet" 
href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {

    console.log("SCRIPT LOADED");

    let dataTable = null;

    // ============================
    //  HANDLE PREVIEW
    // ============================
    $('#formUpload').on('submit', async function(e){
        e.preventDefault();

        const fd = new FormData(this);
        const btn = $(this).find("button");

        btn.text("Loading...").prop("disabled", true);

        try {
            const res = await fetch("{{ route('import.preview') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: fd
            });

            if (!res.ok) throw new Error("Gagal preview");

            const json = await res.json();
            console.log("DATA PREVIEW:", json);

            // HAPUS DATATABLE SEBELUMNYA JIKA ADA
            if (dataTable) {
                dataTable.destroy();
            }

            $("#previewHead").empty();
            $("#previewBody").empty();

            // HEADER
            let header = "<tr>";
            json.preview[0].forEach(h => header += `<th>${h}</th>`);
            header += "</tr>";
            $("#previewHead").append(header);

            // BODY
            json.preview.slice(1).forEach(row => {
                let tr = "<tr>";
                row.forEach(col => tr += `<td>${col ?? ""}</td>`);
                tr += "</tr>";
                $("#previewBody").append(tr);
            });

            // INIT DATATABLE
            dataTable = $("#previewTable").DataTable({
                pageLength: 10,
                lengthMenu: [10, 20, 50, 100, 500, 1000],
                responsive: true,
                ordering: true,
                searching: true
            });

            $("#preview").show();

        } catch (err) {
            console.error(err);
            alert("Terjadi error saat preview.");
        }

        btn.text("Preview").prop("disabled", false);
    });


    // ============================
    //  HANDLE PROCESS IMPORT
    // ============================
    $('#btnProcess').on('click', async function() {

        const btn = $(this);
        btn.text("Processing...").prop("disabled", true);

        try {
            const res = await fetch("{{ route('import.process') }}", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            });

            if (!res.ok) throw new Error("Gagal process");

            const json = await res.json();
            alert(json.message);

            // RESET UI
            $("#preview").hide();
            $("#formUpload")[0].reset();

            if (dataTable) dataTable.destroy();

            $("#previewHead").empty();
            $("#previewBody").empty();

        } catch (err) {
            console.error(err);
            alert("Terjadi error saat process import.");
        }

        btn.text("Process Import").prop("disabled", false);

    });

});
</script>

@endpush
