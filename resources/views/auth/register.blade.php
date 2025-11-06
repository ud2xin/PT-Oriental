<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Sign Up</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-cyan-200 via-blue-300 to-blue-800 p-6">
        <div class="flex bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden max-w-4xl w-full">
            <!-- Sign Up Form Section -->
            <div class="w-1/2 bg-gradient-to-br from-blue-200/50 to-blue-300/50 backdrop-blur-md p-12 flex flex-col justify-center rounded-l-3xl border-r border-white/30">
                <h2 class="text-4xl font-bold text-blue-600 mb-8 text-center">Sign Up</h2>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <input id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="username"
                                placeholder="Email"
                                class="w-full px-4 py-3 bg-transparent border-b-2 border-blue-400 focus:border-blue-600 focus:outline-none text-gray-700 placeholder-gray-600 transition-colors" />
                        @error('email')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <input id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Username"
                                class="w-full px-4 py-3 bg-transparent border-b-2 border-blue-400 focus:border-blue-600 focus:outline-none text-gray-700 placeholder-gray-600 transition-colors" />
                        @error('name')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <input id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Password"
                                class="w-full px-4 py-3 bg-transparent border-b-2 border-blue-400 focus:border-blue-600 focus:outline-none text-gray-700 placeholder-gray-600 transition-colors" />
                        @error('password')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password (Optional - bisa dihapus jika tidak diperlukan) -->
                    <input type="hidden" id="password_confirmation" name="password_confirmation" value="">

                    <!-- Sign Up Button -->
                    <div class="pt-4">
                        <button type="submit"
                                class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-full shadow-lg transition-all duration-300 transform hover:scale-[1.02]">
                            Sign up
                        </button>
                    </div>

                    <!-- Link -->
                    <div class="text-center text-sm pt-2">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                                class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                                Already have an account?
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Logo Section -->
            <div class="w-1/2 bg-white p-12 flex items-center justify-center">
                <img src="{{ asset('images/oei-logo.png') }}" alt="OEI Logo" class="w-full max-w-sm object-contain">
            </div>
        </div>
    </div>

    <script>
        // Auto-fill password confirmation with password value
        document.getElementById('password').addEventListener('input', function() {
            document.getElementById('password_confirmation').value = this.value;
        });
    </script>
</body>
</html>
