<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Welcome to the Dashboard!') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl overflow-hidden mb-8">
                <div class="px-8 py-10 sm:px-12 sm:py-14 text-white">
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-4">Hello, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-blue-100 text-lg max-w-2xl leading-relaxed">
                        Welcome back to your voting platform. You are currently logged in as a <strong>{{ auth()->user()->is_admin || auth()->user()->account_type === 'admin' ? 'System Administrator' : 'Voter' }}</strong>.
                    </p>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Go to Polls Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-8">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-6">
                            <span class="text-2xl">🗳️</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Explore Topics</h3>
                        <p class="text-gray-500 mb-6">Browse all available polls, cast your votes, and see what others are thinking right now.</p>
                        <a href="{{ route('topics.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-green-600 hover:bg-green-700 transition-colors w-full sm:w-auto">
                            View All Polls
                        </a>
                    </div>
                </div>

                @if(auth()->user()->is_admin || auth()->user()->account_type === 'admin')
                <!-- Admin Panel Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-8">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                            <span class="text-2xl">⚙️</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Admin Dashboard</h3>
                        <p class="text-gray-500 mb-6">Create new topics, manage existing polls, and view detailed voting statistics and charts.</p>
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition-colors w-full sm:w-auto">
                            Go to Admin Panel
                        </a>
                    </div>
                </div>
                @else
                <!-- User Stats Card (Optional) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 hover:shadow-lg transition-shadow duration-300">
                    <div class="p-8">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-6">
                            <span class="text-2xl">⭐</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Your Activity</h3>
                        <p class="text-gray-500 mb-6">Thank you for participating! Your voice matters in shaping the results.</p>
                        <!-- Quick logout button for convenience -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors w-full sm:w-auto">
                                Logout / Switch Account
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
