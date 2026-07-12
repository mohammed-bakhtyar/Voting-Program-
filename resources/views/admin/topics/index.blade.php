@extends('layouts.main')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Admin Panel - Topics</h1>
        <a href="{{ route('admin.topics.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Create Topic</a>
    </div>

    @if($topics->isEmpty())
        <p>No topics found.</p>
    @else
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b text-left">Title</th>
                    <th class="py-2 px-4 border-b text-left">Status</th>
                    <th class="py-2 px-4 border-b text-left">Votes</th>
                    <th class="py-2 px-4 border-b text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topics as $topic)
                    <tr>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('admin.topics.show', $topic) }}" class="text-blue-600 font-semibold">{{ $topic->title }}</a>
                        </td>
                        <td class="py-2 px-4 border-b">
                            @if($topic->closed_at || now()->isAfter($topic->closes_at))
                                <span class="text-red-500">Closed</span>
                            @elseif(now()->isBefore($topic->opens_at))
                                <span class="text-yellow-500">Upcoming</span>
                            @else
                                <span class="text-green-500">Open</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b">{{ $topic->votes_count }}</td>
                        <td class="py-2 px-4 border-b flex space-x-2">
                            <a href="{{ route('admin.topics.show', $topic) }}" class="text-blue-500">View</a>
                            @if($topic->votes_count === 0)
                                <a href="{{ route('admin.topics.edit', $topic) }}" class="text-yellow-500">Edit</a>
                                <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" class="inline" onsubmit="return confirm('Delete topic?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500">Delete</button>
                                </form>
                            @endif
                            @if(!$topic->closed_at && now()->isBefore($topic->closes_at))
                                <form action="{{ route('admin.topics.close', $topic) }}" method="POST" class="inline" onsubmit="return confirm('Close topic early?');">
                                    @csrf
                                    <button type="submit" class="text-orange-500">Close Early</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
