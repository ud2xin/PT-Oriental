@extends('layouts.app')
@section('title', 'Import Data Fingerprint')

@section('content')
<div class="container-fluid">

    {{-- PAGE TITLE --}}
    <div class="d-flex align-items-center mb-4 px-2">
        <h4 class="font-weight-bold mb-0 text-primary">Import Data Fingerprint</h4>
    </div>

    {{-- CARD --}}
    <div class="card shadow mb-4">

        <div class="card-body">

            {{-- FORM UPLOAD --}}
            <form id="formUpload" enctype="multipart/form-data">
                @csrf

                <div class="row align-items-end">

                    <div class="col-md-4 mb-3">
                        <label class="font-weight-bold">Pilih File</label>
                        <input type="file" name="file" id="file" accept=".xls,.xlsx,.csv" class="form-control" required>
                    </div>

                    <div class="col-md-2 mb-3">
                        <button class="btn btn-primary btn-block mt-4" type="submit">
                            Preview
                        </button>
                    </div>

                </div>
            </form>

            <hr>

            {{-- PREVIEW AREA --}}
            <div id="preview" style="display:none">

                <h6 class="font-weight-bold mb-3 text-primary">Preview Data</h6>

                <div class="table-responsive">
                    <table class="table table-bordered table-sm" id="tblPreview">
                        <thead class="table-secondary">
                            <tr>
                                <th>Timestamp</th>
                                <th>PIN</th>
                                <th>NIP</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <button id="btnProcess" class="btn btn-success mt-3">
                    Process Import
                </button>

            </div>

        </div>
    </div>

</div>
@endsection


@push('scripts')
<script>
    $(document).ready(function() {

        // PREVIEW BUTTON
        $('#formUpload').on('submit', async function(e) {
            e.preventDefault();

            let fd = new FormData(this);
            let button = $(this).find('button');
            button.text('Loading...').prop('disabled', true);

            try {
                const res = await fetch("{{ route('import.preview') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: fd
                });

                const json = await res.json();

                let tbody = $("#tblPreview tbody").empty();

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
        $('#btnProcess').on('click', async function() {
            let button = $(this);
            button.text('Processing...').prop('disabled', true);

            try {
                const res = await fetch("{{ route('import.process') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
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