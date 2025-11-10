<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Sign Up</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Smooth fade + slide animations */
        @keyframes fadeUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeUp {
            opacity: 0; /* start hidden */
            animation: fadeUp 0.8s ease-out forwards;
        }

        /* Staggered delays */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }

        /* Optional button hover */
        .btn-hover {
            transition: all 0.3s ease;
        }
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body class="bg-gradient-to-r from-cyan-200 via-blue-300 to-blue-800 min-h-screen flex items-center justify-center p-6">
    <div class="flex bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden max-w-4xl w-full animate-fadeUp delay-100">

        <!-- Sign Up Form Section -->
        <div class="w-1/2 bg-gradient-to-br from-blue-200/50 to-blue-300/50 backdrop-blur-md p-12 flex flex-col justify-center rounded-l-3xl border-r border-white/30 animate-fadeUp delay-200">
            <h2 class="text-4xl font-bold text-blue-600 mb-8 text-center animate-fadeUp delay-300">Sign Up</h2>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 animate-fadeUp delay-400">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5 animate-fadeUp delay-400">
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
                            class="w-full px-4 py-3 bg-white/80 border-b-2 border-blue-400 focus:border-blue-600 focus:outline-none text-gray-700 placeholder-gray-600 transition-colors rounded-sm" />
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
                            class="w-full px-4 py-3 bg-white/80 border-b-2 border-blue-400 focus:border-blue-600 focus:outline-none text-gray-700 placeholder-gray-600 transition-colors rounded-sm" />
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
                            class="w-full px-4 py-3 bg-white/80 border-b-2 border-blue-400 focus:border-blue-600 focus:outline-none text-gray-700 placeholder-gray-600 transition-colors rounded-sm" />
                    @error('password')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hidden Confirm Password -->
                <input type="hidden" id="password_confirmation" name="password_confirmation" value="">

                <!-- Sign Up Button -->
                <div class="pt-4 animate-fadeUp delay-500">
                    <button type="submit"
                            class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-full shadow-lg btn-hover">
                        Sign up
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center text-sm pt-2 animate-fadeUp delay-500">
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
        <div class="w-1/2 bg-white p-12 flex items-center justify-center animate-fadeUp delay-200">
            <img src="{{ asset('images/oei-logo.png') }}" alt="OEI Logo" class="w-full max-w-sm object-contain">
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
