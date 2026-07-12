@extends('layouts.main')

@section('content')
<div class="bg-white p-6 rounded shadow max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Edit Topic: {{ $topic->title }}</h1>
    
    <form action="{{ route('admin.topics.update', $topic) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Title</label>
            <input type="text" name="title" value="{{ old('title', $topic->title) }}" class="w-full border rounded px-3 py-2" required>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4" required>{{ old('description', $topic->description) }}</textarea>
        </div>
        
        <div class="flex space-x-4 mb-4">
            <div class="w-1/2">
                <label class="block text-gray-700 font-bold mb-2">Opens At</label>
                <input type="datetime-local" name="opens_at" value="{{ old('opens_at', $topic->opens_at->format('Y-m-d\TH:i')) }}" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="w-1/2">
                <label class="block text-gray-700 font-bold mb-2">Closes At</label>
                <input type="datetime-local" name="closes_at" value="{{ old('closes_at', $topic->closes_at->format('Y-m-d\TH:i')) }}" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Options (2-10 required)</label>
            <div id="options-container" class="space-y-2">
                @php $oldOptions = old('options', $topic->options->pluck('label')->toArray()); @endphp
                @foreach($oldOptions as $index => $option)
                    <input type="text" name="options[]" value="{{ $option }}" class="w-full border rounded px-3 py-2 {{ $index > 0 ? 'mt-2' : '' }}" placeholder="Option {{ $index + 1 }}" required>
                @endforeach
            </div>
            <button type="button" id="add-option" class="mt-2 text-blue-600 font-semibold">+ Add Option</button>
        </div>
        
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700">Update Topic</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('options-container');
        const addButton = document.getElementById('add-option');
        
        addButton.addEventListener('click', function() {
            if (container.children.length < 10) {
                const input = document.createElement('input');
                input.type = 'text';
                input.name = 'options[]';
                input.className = 'w-full border rounded px-3 py-2 mt-2';
                input.placeholder = 'Option ' + (container.children.length + 1);
                input.required = true;
                container.appendChild(input);
            }
            if (container.children.length >= 10) {
                addButton.style.display = 'none';
            }
        });
    });
</script>
@endsection
