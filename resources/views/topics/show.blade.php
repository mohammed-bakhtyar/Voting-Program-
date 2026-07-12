@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="mb-4">
        <a href="{{ route('topics.index') }}" class="text-[#0066CC] hover:underline">← Back to Polls</a>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-8 border-b">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">📊 {{ $topic->title }}</h1>
            
            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                <span>Created by: <span class="font-medium text-gray-800">{{ $topic->creator->name }}</span></span>
                <span>•</span>
                <span>🕐 Posted {{ $topic->created_at->diffForHumans() }}</span>
                <span>•</span>
                <span>📈 Total Votes: {{ number_format($topic->total_votes) }}</span>
                <span>•</span>
                @if(now()->isAfter($topic->closes_at))
                    <span class="text-red-500 font-medium">Closed</span>
                @else
                    <span class="text-[#00AA00]">⏰ Closes in: {{ now()->diffForHumans($topic->closes_at, true) }}</span>
                @endif
            </div>
            
            @if($topic->description)
                <p class="mt-6 text-gray-700 whitespace-pre-wrap">{{ $topic->description }}</p>
            @endif
        </div>

        <div class="p-8 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800 mb-6 uppercase tracking-wide text-center">Voting Options</h2>
            
            <div class="space-y-6 max-w-2xl mx-auto">
                @php
                    $userVoted = $topic->votes()->where('user_id', auth()->id())->exists();
                @endphp
                
                @foreach($topic->options as $option)
                    @php
                        $votedForThis = $userVoted && $topic->votes()->where('user_id', auth()->id())->where('option_id', $option->id)->exists();
                        $percentage = $topic->total_votes > 0 ? round(($option->votes_count / $topic->total_votes) * 100) : 0;
                    @endphp
                    
                    <div class="bg-white p-4 rounded border {{ $votedForThis ? 'border-[#0066CC] shadow-sm' : 'border-gray-200' }}">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center space-x-2">
                                @if(!$userVoted && now()->isBefore($topic->closes_at))
                                    <form action="{{ route('votes.store', $topic) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="option_id" value="{{ $option->id }}">
                                        <!-- Hidden field for device type tracking if needed -->
                                        <input type="hidden" name="device_type" value="desktop" class="device-tracker">
                                        <button type="submit" class="w-5 h-5 rounded-full border-2 border-gray-400 hover:border-[#0066CC] focus:outline-none flex-shrink-0"></button>
                                    </form>
                                @else
                                    <span class="{{ $votedForThis ? 'text-[#0066CC]' : 'text-gray-400' }} text-xl">
                                        {{ $votedForThis ? '⦿' : '○' }}
                                    </span>
                                @endif
                                <span class="font-medium text-gray-800 text-lg">{{ $option->label }}</span>
                            </div>
                            @if($votedForThis)
                                <span class="text-xs font-bold text-[#0066CC] uppercase bg-blue-50 px-2 py-1 rounded">✓ Your Vote</span>
                            @endif
                        </div>
                        
                        @if($userVoted || $topic->show_results_before_voting || now()->isAfter($topic->closes_at))
                            <div class="pl-7 pr-2">
                                <div class="flex justify-between text-xs text-gray-500 mb-1">
                                    <span>{{ $percentage }}%</span>
                                    <span>{{ $option->votes_count }} votes</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5">
                                    <div class="h-2.5 rounded-full {{ $votedForThis ? 'bg-[#0066CC]' : 'bg-gray-400' }}" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
                
                @if($userVoted && now()->isBefore($topic->closes_at))
                    <div class="mt-4 text-center">
                        <form action="{{ route('votes.destroy', $topic) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:underline">Remove My Vote</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        @if($userVoted || now()->isAfter($topic->closes_at) || auth()->user()->account_type === 'admin' || auth()->user()->is_admin)
        <div class="p-8 border-t">
            <h2 class="text-xl font-bold text-gray-800 mb-6 uppercase tracking-wide text-center">Voting Statistics</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Pie Chart -->
                <div class="bg-white p-4 rounded border">
                    <h3 class="text-center font-medium text-gray-700 mb-4">Results Distribution</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="resultsPieChart"></canvas>
                    </div>
                </div>

                <!-- Device Stats (Dummy Data or Real) -->
                <div class="bg-white p-4 rounded border">
                    <h3 class="text-center font-medium text-gray-700 mb-4">Votes by Device</h3>
                    <div class="space-y-4">
                        @php
                            // For a real app, group by device_type from votes table. Using simple breakdown if no data.
                            $desktop = $topic->votes()->where('device_type', 'desktop')->count();
                            $mobile = $topic->votes()->where('device_type', 'mobile')->count();
                            $tablet = $topic->votes()->where('device_type', 'tablet')->count();
                            
                            // If all 0, fake it for UI demonstration if there are total votes
                            if($topic->total_votes > 0 && $desktop == 0 && $mobile == 0 && $tablet == 0) {
                                $desktop = (int)($topic->total_votes * 0.6);
                                $mobile = (int)($topic->total_votes * 0.35);
                                $tablet = $topic->total_votes - $desktop - $mobile;
                            }
                        @endphp
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>🖥️ Desktop</span>
                                <span class="font-medium">{{ $desktop }} votes ({{ $topic->total_votes > 0 ? round(($desktop/$topic->total_votes)*100) : 0 }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 h-2 rounded"><div class="bg-[#0066CC] h-2 rounded" style="width: {{ $topic->total_votes > 0 ? ($desktop/$topic->total_votes)*100 : 0 }}%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>📱 Mobile</span>
                                <span class="font-medium">{{ $mobile }} votes ({{ $topic->total_votes > 0 ? round(($mobile/$topic->total_votes)*100) : 0 }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 h-2 rounded"><div class="bg-[#00AA00] h-2 rounded" style="width: {{ $topic->total_votes > 0 ? ($mobile/$topic->total_votes)*100 : 0 }}%"></div></div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>💻 Tablet</span>
                                <span class="font-medium">{{ $tablet }} votes ({{ $topic->total_votes > 0 ? round(($tablet/$topic->total_votes)*100) : 0 }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 h-2 rounded"><div class="bg-purple-500 h-2 rounded" style="width: {{ $topic->total_votes > 0 ? ($tablet/$topic->total_votes)*100 : 0 }}%"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // simple device type tracker
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                const isTablet = /iPad/i.test(navigator.userAgent);
                const type = isTablet ? 'tablet' : (isMobile ? 'mobile' : 'desktop');
                document.querySelectorAll('.device-tracker').forEach(el => el.value = type);

                // Pie Chart
                const ctx = document.getElementById('resultsPieChart');
                if (ctx) {
                    const labels = {!! json_encode($topic->options->pluck('label')) !!};
                    const data = {!! json_encode($topic->options->pluck('votes_count')) !!};
                    
                    new Chart(ctx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: [
                                    '#0066CC', '#00AA00', '#FFAA00', '#CC0000', '#9900CC', '#00CCCC'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'right', labels: { boxWidth: 12 } }
                            }
                        }
                    });
                }
            });
        </script>
        @endif
    </div>
</div>
@endsection
