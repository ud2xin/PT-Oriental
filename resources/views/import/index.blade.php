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
$('#formUpload').on('submit', async function(e){
    e.preventDefault();
    const fd = new FormData(this);
    const res = await fetch("{{ route('import.preview') }}", { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}, body:fd });
    const json = await res.json();
    // render preview
    let tbody = $('#tblPreview tbody').empty();
    json.preview.forEach((r,i)=> tbody.append(`<tr><td>${i+1}</td><td>${r[0]}</td><td>${r[1]}</td></tr>`));
    $('#preview').show();
});

$('#btnProcess').on('click', async function(){
    const res = await fetch("{{ route('import.process') }}", { method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});
    const json = await res.json();
    alert(json.message || 'Done');
});
</script>
@endpush
