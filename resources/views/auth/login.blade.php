<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'Laravel') }} - Login</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<style>
    @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeUp {
        opacity: 0;
        animation: fadeUp 0.8s ease-out forwards;
    }

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
    <div id="login-container" class="flex bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden max-w-4xl w-full">

        <!-- Logo Section -->
        <div class="w-1/2 bg-white p-12 flex items-center justify-center">
            <img id="logo" src="{{ asset('images/oei-logo.png') }}" alt="OEI Logo" class="w-full max-w-sm object-contain">
        </div>

        <!-- Login Form Section -->
        <div class="w-1/2 bg-gradient-to-br from-blue-200/50 to-blue-300/50 backdrop-blur-md p-12 flex flex-col justify-center">
            <h2 class="text-4xl font-bold text-blue-600 mb-8 text-center">Login</h2>

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    placeholder="Email/Username" class="w-full px-4 py-3 bg-white/80 border-b-2 border-blue-400 focus:border-blue-600 focus:outline-none text-gray-700 placeholder-gray-600 rounded-sm" />
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Password"
                    class="w-full px-4 py-3 bg-white/80 border-b-2 border-blue-400 focus:border-blue-600 focus:outline-none text-gray-700 placeholder-gray-600 rounded-sm" />
                    @error('password')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-full shadow-lg btn-hover">
                        Login
                    </button>
                </div>

                <div class="flex items-center justify-between text-sm pt-2">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Create an account</a>
                    @endif
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Forget password?</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <script>
        // Staggered fade-up animations including logo
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('login-container');
            const items = container.querySelectorAll('#logo, h2, form div, form button, form a');
            items.forEach((el, i) => {
                el.style.animationDelay = `${i * 0.15}s`;
                el.classList.add('animate-fadeUp');
            });
        });
    </script>
</body>
</html>
