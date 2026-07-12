@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Available Polls</h1>
        <div class="flex space-x-2">
            <!-- Simplified filters -->
            <form action="{{ route('topics.index') }}" method="GET" class="m-0">
                <select name="sort" onchange="this.form.submit()" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 font-medium text-gray-700">
                    <option value="latest" {{ (isset($sort) && $sort === 'latest') ? 'selected' : '' }}>Latest</option>
                    <option value="popular" {{ (isset($sort) && $sort === 'popular') ? 'selected' : '' }}>Most Voted</option>
                </select>
            </form>
        </div>
    </div>

    @if($topics->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow text-center text-gray-500">
            No topics available right now.
        </div>
    @else
        <div class="space-y-6">
            @foreach($topics as $topic)
                @php
                    $userVoted = $topic->votes()->where('user_id', auth()->id())->exists();
                @endphp
                
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div class="p-6">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-gray-800 mb-1">
                                    <a href="{{ route('topics.show', $topic) }}" class="hover:text-[#0066CC]">
                                        {{ $topic->title }}
                                    </a>
                                </h2>
                                <p class="text-sm text-gray-500 mb-4">
                                    Created by {{ $topic->creator->name }} • {{ $topic->created_at->diffForHumans() }}
                                    @if($userVoted)
                                        <span class="ml-2 text-[#00AA00] font-medium bg-green-50 px-2 py-0.5 rounded">✓ You Voted</span>
                                    @endif
                                </p>

                                <div class="space-y-3">
                                    @foreach($topic->options as $option)
                                        @php
                                            $votedForThis = $userVoted && $topic->votes()->where('user_id', auth()->id())->where('option_id', $option->id)->exists();
                                            $percentage = $topic->total_votes > 0 ? round(($option->votes_count / $topic->total_votes) * 100) : 0;
                                        @endphp
                                        
                                        @if($userVoted || $topic->show_results_before_voting)
                                            <!-- After Vote or Results enabled -->
                                            <div class="relative">
                                                <div class="flex justify-between text-sm mb-1">
                                                    <span class="{{ $votedForThis ? 'font-bold text-[#0066CC]' : 'text-gray-700' }} flex items-center gap-2">
                                                        @if(!$userVoted && now()->isBefore($topic->closes_at))
                                                            <form action="{{ route('votes.store', $topic) }}" method="POST" class="inline m-0 h-4">
                                                                @csrf
                                                                <input type="hidden" name="option_id" value="{{ $option->id }}">
                                                                <button type="submit" class="w-4 h-4 rounded-full border-2 border-gray-400 hover:border-[#0066CC] focus:outline-none flex items-center justify-center bg-white"></button>
                                                            </form>
                                                        @else
                                                            <span class="text-lg leading-none">{{ $votedForThis ? '⦿' : '○' }}</span>
                                                        @endif
                                                        {{ $option->label }}
                                                    </span>
                                                    <span class="text-gray-500 font-medium">{{ $percentage }}% ({{ $option->votes_count }} votes)</span>
                                                </div>
                                                <div class="w-full bg-gray-100 rounded-full h-2">
                                                    <div class="h-2 rounded-full {{ $votedForThis ? 'bg-[#0066CC]' : 'bg-gray-400' }}" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Before Vote -->
                                            <div class="flex items-center space-x-2 p-2 hover:bg-gray-50 rounded group">
                                                @if(!$userVoted && now()->isBefore($topic->closes_at))
                                                    <form action="{{ route('votes.store', $topic) }}" method="POST" class="inline m-0 h-5">
                                                        @csrf
                                                        <input type="hidden" name="option_id" value="{{ $option->id }}">
                                                        <button type="submit" class="w-5 h-5 rounded-full border-2 border-gray-400 hover:border-[#0066CC] group-hover:border-[#0066CC] focus:outline-none flex items-center justify-center bg-white"></button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 text-xl leading-none">○</span>
                                                @endif
                                                <span class="text-gray-700 font-medium">{{ $option->label }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="mt-6 flex items-center justify-between border-t pt-4 text-sm">
                                    <div class="text-gray-500">
                                        @if(now()->isAfter($topic->closes_at))
                                            <span class="text-red-500 font-medium">Closed</span>
                                        @else
                                            Closes in {{ now()->diffForHumans($topic->closes_at, true) }}
                                        @endif
                                    </div>
                                    <div class="space-x-3">
                                        <a href="{{ route('topics.show', $topic) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 font-semibold rounded-lg hover:bg-blue-100 transition-colors border border-blue-200 shadow-sm">
                                            View Details
                                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
