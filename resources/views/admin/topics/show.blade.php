@extends('layouts.main')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold">{{ $topic->title }}</h1>
        <a href="{{ route('admin.topics.index') }}" class="text-blue-600 underline">Back to Topics</a>
    </div>
    
    <p class="text-gray-700 mb-6 whitespace-pre-wrap">{{ $topic->description }}</p>

    <div class="mb-6 p-4 bg-gray-50 rounded text-sm grid grid-cols-2 gap-4">
        <div><strong>Opens:</strong> {{ $topic->opens_at->format('M d, Y H:i') }}</div>
        <div><strong>Closes:</strong> {{ $topic->closes_at->format('M d, Y H:i') }}</div>
        <div><strong>Status:</strong> 
            @if($topic->closed_at || now()->isAfter($topic->closes_at))
                <span class="text-red-500 font-bold">Closed</span>
            @elseif(now()->isBefore($topic->opens_at))
                <span class="text-yellow-500 font-bold">Upcoming</span>
            @else
                <span class="text-green-500 font-bold">Open</span>
            @endif
        </div>
        <div><strong>Total Votes:</strong> {{ $topic->votes_count ?? 0 }}</div>
    </div>

    <div class="mb-8 border-t pt-8">
        <h2 class="text-2xl font-bold mb-4">Results</h2>
        <div class="space-y-4">
            @foreach($topic->options as $option)
                @php
                    $percentage = ($topic->votes_count ?? 0) > 0 ? round(($option->votes_count / $topic->votes_count) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="font-semibold">{{ $option->label }}</span>
                        <span>{{ $option->votes_count }} votes ({{ $percentage }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
