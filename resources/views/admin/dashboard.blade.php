@extends('layouts.main')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">Admin Statistics Panel</h1>
            <a href="{{ route('admin.topics.create') }}" class="px-4 py-2 bg-[#0066CC] text-white font-bold rounded-lg shadow hover:bg-blue-700 transition">
                + Create New Topic
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-blue-50 border-l-4 border-blue-600 p-6 rounded-r-lg">
                <h3 class="text-sm font-semibold text-blue-800 uppercase tracking-wider">Total Polls</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['total_polls'] }}</p>
                <div class="mt-2 text-sm text-blue-600">
                    <span class="font-medium">{{ $stats['active_polls'] }} Active</span> &bull; 
                    <span class="font-medium">{{ $stats['closed_polls'] }} Closed</span>
                </div>
            </div>
            
            <div class="bg-green-50 border-l-4 border-green-600 p-6 rounded-r-lg">
                <h3 class="text-sm font-semibold text-green-800 uppercase tracking-wider">Total Votes</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['total_votes']) }}</p>
            </div>

            <div class="bg-purple-50 border-l-4 border-purple-600 p-6 rounded-r-lg">
                <h3 class="text-sm font-semibold text-purple-800 uppercase tracking-wider">Total Users</h3>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ number_format($stats['total_users']) }}</p>
                <div class="mt-2 text-sm text-purple-600">
                    Avg {{ round($stats['total_polls'] > 0 ? $stats['total_votes'] / $stats['total_polls'] : 0, 1) }} votes per poll
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Most Active Topics -->
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4">Most Active Topics (This Month)</h2>
                <div class="space-y-4">
                    @forelse($stats['most_active_topics'] as $index => $topic)
                        @php
                            $maxVotes = $stats['most_active_topics']->max('total_votes') ?: 1;
                            $percentage = ($topic->total_votes / $maxVotes) * 100;
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700">{{ $index + 1 }}. {{ Str::limit($topic->title, 30) }}</span>
                                <span class="text-gray-500">{{ $topic->total_votes }} votes</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-[#0066CC] h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No topics available yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Voting Trend -->
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4">Voting Trend (Last 7 Days)</h2>
                <div class="relative h-64 w-full">
                    <canvas id="votingTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('votingTrendChart').getContext('2d');
        
        // Pass data from backend
        const dates = {!! json_encode($stats['trend_dates']) !!};
        const votes = {!! json_encode($stats['trend_votes']) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Daily Votes',
                    data: votes,
                    borderColor: '#0066CC',
                    backgroundColor: 'rgba(0, 102, 204, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endsection
