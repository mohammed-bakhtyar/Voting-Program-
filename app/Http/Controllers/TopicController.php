<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Topic;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'latest');
        $query = Topic::where('status', '!=', 'draft');
        
        if ($sort === 'popular') {
            $topics = $query->withCount('votes')->orderByDesc('votes_count')->latest()->get();
        } else {
            $topics = $query->latest()->get();
        }

        return view('topics.index', compact('topics', 'sort'));
    }

    public function show(Topic $topic)
    {
        $topic->load(['options' => function($q) { $q->orderBy('display_order'); }]);
        
        $user = auth()->user();
        $hasVoted = $user ? $topic->votes()->where('user_id', $user->id)->exists() : false;
        
        $isClosed = $topic->closed_at !== null || now()->isAfter($topic->closes_at);
        $showResults = $hasVoted || $isClosed;

        if ($showResults) {
            $topic->loadCount('votes');
            $topic->load(['options' => function($q) { $q->withCount('votes')->orderBy('display_order'); }]);
        }

        $userVote = $user ? $topic->votes()->where('user_id', $user->id)->first() : null;

        return view('topics.show', compact('topic', 'showResults', 'userVote', 'isClosed'));
    }
}
