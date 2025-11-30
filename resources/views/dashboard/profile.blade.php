@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Profil Saya</h1>

    <div class="row">
        
        <div class="col-lg-12">
            
            {{-- Pesan Sukses/Error --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Profil</h6>
                </div>
                <div class="card-body">
                    
                    {{-- FORM UPDATE PROFIL --}}
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            {{-- KOLOM KIRI: FOTO --}}
                            <div class="col-md-4 text-center mb-4">
                                @php
                                    $userPhoto = auth()->user()->photo;
                                    $photoExists = $userPhoto && file_exists(public_path('photos/' . $userPhoto));
                                @endphp

                                <img id="preview-image" 
                                     src="{{ $photoExists ? asset('photos/'.$userPhoto) : asset('sb-admin-2/img/undraw_profile.svg') }}" 
                                     class="rounded-circle img-thumbnail mb-3" 
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                
                                <div class="custom-file text-left mt-2">
                                    <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*" onchange="previewImage()">
                                    <label class="custom-file-label" for="photo" data-browse="Pilih">Ubah Foto</label>
                                </div>
                                <small class="text-muted d-block mt-2">Format: JPG, JPEG, PNG. Max: 2MB</small>
                            </div>

                            {{-- KOLOM KANAN: DATA DIRI --}}
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Alamat Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="form-group text-right mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save fa-sm text-white-50 mr-1"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>

                    <hr class="my-4">

                    {{-- FORM GANTI PASSWORD --}}
                    <h6 class="font-weight-bold text-primary mb-3">Ganti Password</h6>
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Password Saat Ini</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Password Baru</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key fa-sm text-white-50 mr-1"></i> Update Password
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<script>
    function previewImage() {
        const file = document.getElementById("photo").files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("preview-image").src = e.target.result;
            }
            reader.readAsDataURL(file);
            
            // Ubah label input file menjadi nama file
            const fileName = file.name;
            document.querySelector('.custom-file-label').textContent = fileName;
        }
    }
</script>

@endsection
