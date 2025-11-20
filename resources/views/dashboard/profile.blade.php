@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Profil Saya</h6>
        <span class="badge badge-success">Online</span>
    </div>

    <div class="card-body">

        {{-- SUCCESS & ERROR --}}
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">

            {{-- LEFT: PHOTO --}}
            <div class="col-md-4 text-center">
                <img id="profileImage"
                    src="{{ $user->photo ? asset('images/'.$user->photo) : asset('sb-admin-2/img/undraw_profile.svg') }}"
                    class="rounded-circle mb-3" width="150">

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="photo" class="form-control mb-2" onchange="previewImage(event)">
                    @error('photo')
                    <small class="text-danger">{{ $message }}</small>
                    @enderror
                    <button class="btn btn-primary btn-sm mt-2">Update Foto</button>
                </form>
            </div>

            {{-- RIGHT: DATA & PASSWORD --}}
            <div class="col-md-8">

                {{-- Update profile data --}}
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                    </div>
                    <button class="btn btn-success mt-2">Simpan Perubahan</button>
                </form>

                <hr>

                {{-- Update password --}}
                <h6 class="font-weight-bold text-primary mt-4">Ubah Password</h6>
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Password Lama</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <button class="btn btn-warning mt-2">Update Password</button>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- Preview foto sebelum submit --}}
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('profileImage');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection