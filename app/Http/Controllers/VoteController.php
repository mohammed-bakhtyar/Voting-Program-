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

        $userId   = $request->user()->id;
        $optionId = $request->option_id;

        // Check if user already voted for this specific option
        $existingVote = $topic->votes()->where('user_id', $userId)->where('option_id', $optionId)->first();

        if ($existingVote) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Already voted for this option.']);
            }
            return back()->with('success', 'You already voted for this option.');
        }

        if (!$topic->allow_multiple_votes) {
            // Remove previous vote if single vote only
            $previousVote = $topic->votes()->where('user_id', $userId)->first();
            if ($previousVote) {
                $previousOption = $topic->options()->find($previousVote->option_id);
                if ($previousOption) {
                    $previousOption->decrement('votes_count');
                }
                $previousVote->delete();
                $topic->decrement('total_votes');
            }
        }

        // Create new vote
        Vote::create([
            'user_id'   => $userId,
            'topic_id'  => $topic->id,
            'option_id' => $optionId,
        ]);

        $topic->options()->find($optionId)->increment('votes_count');
        $topic->increment('total_votes');

        if ($request->ajax() || $request->wantsJson()) {
            $topic->refresh();
            $topic->load(['options' => fn($q) => $q->orderBy('display_order')]);
            return response()->json([
                'success'     => true,
                'voted_option'=> (int) $optionId,
                'total_votes' => $topic->total_votes,
                'options'     => $topic->options->map(fn($o) => [
                    'id'          => $o->id,
                    'votes_count' => $o->votes_count,
                    'percentage'  => $topic->total_votes > 0 ? round(($o->votes_count / $topic->total_votes) * 100) : 0,
                ]),
            ]);
        }

        return back()->with('success', 'Vote cast successfully.');
    }

    public function destroy(Request $request, Topic $topic)
    {
        $this->authorize('vote', $topic);

        $votes = $topic->votes()->where('user_id', $request->user()->id)->get();

        foreach ($votes as $vote) {
            $option = $topic->options()->find($vote->option_id);
            if ($option) {
                $option->decrement('votes_count');
            }
            $vote->delete();
            $topic->decrement('total_votes');
        }

        if ($request->ajax() || $request->wantsJson()) {
            $topic->refresh();
            $topic->load(['options' => fn($q) => $q->orderBy('display_order')]);
            return response()->json([
                'success'     => true,
                'voted_option'=> null,
                'total_votes' => $topic->total_votes,
                'options'     => $topic->options->map(fn($o) => [
                    'id'          => $o->id,
                    'votes_count' => $o->votes_count,
                    'percentage'  => $topic->total_votes > 0 ? round(($o->votes_count / $topic->total_votes) * 100) : 0,
                ]),
            ]);
        }

        return back()->with('success', 'Vote withdrawn successfully.');
    }
}
