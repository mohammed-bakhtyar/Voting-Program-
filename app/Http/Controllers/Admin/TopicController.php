<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Topic;
use App\Models\Option;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TopicController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $topics = Topic::withCount('votes')->latest()->get();
        return view('admin.topics.index', compact('topics'));
    }

    public function create()
    {
        return view('admin.topics.wizard');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'opens_at' => 'required|date',
            'closes_at' => 'required|date|after:opens_at',
            'options' => 'required|array|min:2|max:10',
            'options.*' => 'required|string|max:255',
        ]);

        $topic = $request->user()->topics()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'opens_at' => $validated['opens_at'],
            'closes_at' => $validated['closes_at'],
        ]);

        foreach ($validated['options'] as $index => $label) {
            $topic->options()->create([
                'label' => $label,
                'display_order' => $index,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json(['id' => $topic->id]);
        }

        return redirect()->route('admin.topics.index')->with('success', 'Topic created successfully.');
    }

    public function show(Topic $topic)
    {
        $topic->load(['options' => function($q) { $q->withCount('votes')->orderBy('display_order'); }]);
        $topic->loadCount('votes');
        return view('admin.topics.show', compact('topic'));
    }

    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        return view('admin.topics.edit', compact('topic'));
    }

    public function update(Request $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'opens_at' => 'required|date',
            'closes_at' => 'required|date|after:opens_at',
            'options' => 'required|array|min:2|max:10',
            'options.*' => 'required|string|max:255',
        ]);

        $topic->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'opens_at' => $validated['opens_at'],
            'closes_at' => $validated['closes_at'],
        ]);

        $topic->options()->delete();
        foreach ($validated['options'] as $index => $label) {
            $topic->options()->create([
                'label' => $label,
                'display_order' => $index,
            ]);
        }

        return redirect()->route('admin.topics.index')->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('delete', $topic);
        $topic->delete();
        return redirect()->route('admin.topics.index')->with('success', 'Topic deleted successfully.');
    }

    public function close(Topic $topic)
    {
        $topic->update(['closed_at' => now()]);
        return redirect()->route('admin.topics.index')->with('success', 'Topic closed early.');
    }
}
