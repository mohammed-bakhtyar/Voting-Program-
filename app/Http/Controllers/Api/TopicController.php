<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\Option;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $topics = Topic::with(['options'])->withCount('votes')->latest()->get();
        return response()->json($topics);
    }

    public function show(Topic $topic)
    {
        $topic->load('options');
        $topic->loadCount('votes');
        return response()->json($topic);
    }

    public function store(Request $request)
    {
        if (auth()->user()->account_type !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'opens_at' => 'required|date',
            'closes_at' => 'required|date|after:opens_at',
            'options' => 'required|array|min:2|max:10',
            'options.*' => 'required|string|max:255',
            'allow_multiple_votes' => 'boolean',
            'show_results_before_voting' => 'boolean',
            'status' => 'in:draft,active,closed'
        ]);

        $topic = Topic::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'opens_at' => $validated['opens_at'],
            'closes_at' => $validated['closes_at'],
            'created_by' => auth()->id(),
            'allow_multiple_votes' => $request->boolean('allow_multiple_votes'),
            'show_results_before_voting' => $request->boolean('show_results_before_voting'),
            'status' => $validated['status'] ?? 'draft',
        ]);

        foreach ($validated['options'] as $index => $optionText) {
            Option::create([
                'topic_id' => $topic->id,
                'label' => $optionText,
                'display_order' => $index,
            ]);
        }

        return response()->json($topic->load('options'), 201);
    }

    public function update(Request $request, Topic $topic)
    {
        if (auth()->user()->account_type !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'status' => 'in:draft,active,closed'
        ]);

        $topic->update($validated);

        return response()->json($topic);
    }

    public function destroy(Topic $topic)
    {
        if (auth()->user()->account_type !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $topic->delete();

        return response()->json(null, 204);
    }

    public function vote(Request $request, Topic $topic)
    {
        $validated = $request->validate([
            'option_id' => 'required|exists:options,id',
            'device_type' => 'nullable|string'
        ]);

        if ($topic->status !== 'active' && now()->isBefore($topic->opens_at) || now()->isAfter($topic->closes_at)) {
            return response()->json(['message' => 'Voting is not active for this topic'], 403);
        }

        $option = $topic->options()->findOrFail($validated['option_id']);
        
        DB::transaction(function () use ($request, $topic, $option, $validated) {
            if (!$topic->allow_multiple_votes) {
                // Remove previous votes
                $existingVote = Vote::where('user_id', auth()->id())
                    ->where('topic_id', $topic->id)
                    ->first();
                    
                if ($existingVote) {
                    $oldOption = Option::find($existingVote->option_id);
                    $oldOption->decrement('votes_count');
                    $topic->decrement('total_votes');
                    $existingVote->delete();
                }
            }

            Vote::firstOrCreate([
                'user_id' => auth()->id(),
                'topic_id' => $topic->id,
                'option_id' => $option->id,
            ], [
                'ip_address' => $request->ip(),
                'device_type' => $validated['device_type'] ?? 'desktop',
            ]);

            $option->increment('votes_count');
            $topic->increment('total_votes');
        });

        return response()->json(['message' => 'Vote cast successfully']);
    }
}
