<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Form Utama dengan Layout 2 Kolom (Foto Kiri, Data Kanan) --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex flex-col md:flex-row gap-8">
            
            {{-- KOLOM KIRI: FOTO PROFILE --}}
            <div class="w-full md:w-1/3 flex flex-col items-center">
                <div class="relative">
                    {{-- Preview Foto --}}
                    <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-gray-200 shadow-lg">
                        @if($user->photo)
                            <img src="{{ asset('images/' . $user->photo) }}" 
                                 class="w-full h-full object-cover" 
                                 alt="Profile Photo">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" 
                                 class="w-full h-full object-cover" 
                                 alt="Default Avatar">
                        @endif
                    </div>
                </div>

                {{-- Tombol Upload --}}
                <div class="mt-4 w-full text-center">
                    <label for="photo" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 cursor-pointer">
                        {{ __('Change Photo') }}
                    </label>
                    <input id="photo" name="photo" type="file" class="hidden" accept="image/*" onchange="previewImage(event)" />
                    <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                </div>
                <p class="text-xs text-gray-500 mt-2 text-center">JPG, JPEG, PNG (Max 2MB)</p>
            </div>

            {{-- KOLOM KANAN: INPUT DATA --}}
            <div class="w-full md:w-2/3 space-y-4">
                
                {{-- Nama --}}
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                {{-- Email --}}
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-gray-800">
                                {{ __('Your email address is unverified.') }}
                                <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Departemen (Dropdown) --}}
                <div>
                    <x-input-label for="department_id" :value="__('Department')" />
                    <select id="department_id" name="department_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">-- Select Department --</option>
                        {{-- Menggunakan variabel $departments jika dikirim dari controller --}}
                        @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('department_id')" />
                </div>

                {{-- Role (Dropdown) --}}
                <div>
                    <x-input-label for="role" :value="__('Role')" />
                    <select id="role" name="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super-admin" {{ old('role', $user->role) == 'super-admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('role')" />
                </div>

                {{-- NIP (Readonly jika perlu, atau editable) --}}
                <div>
                    <x-input-label for="nip" :value="__('NIP')" />
                    {{-- Saya asumsikan NIP ada di tabel employee yang berelasi, atau kolom nip di user --}}
                    <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full bg-gray-100" :value="old('nip', $user->nip ?? $user->employee->nip ?? '-')" readonly />
                    <p class="text-xs text-gray-500 mt-1">*NIP cannot be changed manually.</p>
                </div>

                {{-- Tombol Save --}}
                <div class="flex items-center gap-4 mt-6">
                    <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </form>
</section>

{{-- Script Kecil untuk Preview Image saat dipilih --}}
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.querySelector('.w-40 img');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
