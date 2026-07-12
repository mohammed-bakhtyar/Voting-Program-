<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPolls = Topic::count();
        $activePolls = Topic::where('status', 'active')->count();
        $closedPolls = Topic::where('status', 'closed')->count();
        
        $totalVotes = Vote::count();
        $totalUsers = User::count();

        $mostActiveTopics = Topic::orderBy('total_votes', 'desc')->take(3)->get();

        // Voting Trend (Last 7 Days)
        $trendDates = [];
        $trendVotes = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $trendDates[] = Carbon::now()->subDays($i)->format('M d');
            $votesOnDate = Vote::whereDate('created_at', $date)->count();
            $trendVotes[] = $votesOnDate;
        }

        $stats = [
            'total_polls' => $totalPolls,
            'active_polls' => $activePolls,
            'closed_polls' => $closedPolls,
            'total_votes' => $totalVotes,
            'total_users' => $totalUsers,
            'most_active_topics' => $mostActiveTopics,
            'trend_dates' => $trendDates,
            'trend_votes' => $trendVotes,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
