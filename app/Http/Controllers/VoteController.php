<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Topic;
use App\Models\Vote;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VoteController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Topic $topic)
    {
        $this->authorize('vote', $topic);

        $request->validate([
            'option_id' => 'required|exists:options,id',
        ]);

        Vote::upsert([
            ['user_id' => $request->user()->id, 'topic_id' => $topic->id, 'option_id' => $request->option_id]
        ], ['user_id', 'topic_id'], ['option_id']);

        return back()->with('success', 'Vote cast successfully.');
    }

    public function destroy(Request $request, Topic $topic)
    {
        $this->authorize('vote', $topic);

        $topic->votes()->where('user_id', $request->user()->id)->delete();

        return back()->with('success', 'Vote withdrawn successfully.');
    }
}
