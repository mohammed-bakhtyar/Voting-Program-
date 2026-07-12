<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Voting App</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased selection:bg-blue-200 selection:text-blue-900">
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('topics.index') }}" class="font-bold text-xl text-blue-600">Voting App</a>
            <div class="flex items-center space-x-3">
                @auth
                    @if(auth()->user()->is_admin || auth()->user()->account_type === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 bg-purple-50 text-purple-700 font-semibold rounded-xl hover:bg-purple-100 transition-colors border border-purple-100">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Admin Panel
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-50 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition-colors border border-gray-200">
                        Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-xl hover:bg-red-100 hover:text-red-700 transition-colors border border-red-100">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2 text-gray-600 font-semibold hover:text-blue-600 transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-sm">Register</a>
                @endauth
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
