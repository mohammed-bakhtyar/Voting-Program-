<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Topic;
use App\Models\Option;
use App\Models\Vote;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin_one',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'account_type' => 'admin',
            'is_admin' => true,
        ]);

        // 2. Create Regular Users
        $users = User::factory(50)->create([
            'account_type' => 'user'
        ]);

        // Topic 1: Vacation
        $topic1 = Topic::create([
            'title' => 'Where Would You Like to Spend Your Vacation?',
            'description' => 'Select your favorite holiday destination from the list below.',
            'created_by' => $admin->id,
            'status' => 'active',
            'allow_multiple_votes' => false,
            'show_results_before_voting' => true,
            'opens_at' => Carbon::now()->subDays(2),
            'closes_at' => Carbon::now()->addDays(5),
            'total_votes' => 45
        ]);

        $options1 = [
            'Dubai' => 15,
            'Istanbul' => 10,
            'Cairo' => 5,
            'Paris' => 10,
            'Tokyo' => 5,
            'New York' => 0
        ];

        foreach ($options1 as $label => $votes) {
            $opt = Option::create(['topic_id' => $topic1->id, 'label' => $label, 'votes_count' => $votes]);
            $shuffledUsers = clone $users;
            $shuffledUsers = $shuffledUsers->shuffle();
            for ($i=0; $i<$votes; $i++) {
                $user = $shuffledUsers->pop();
                Vote::create(['user_id' => $user->id, 'topic_id' => $topic1->id, 'option_id' => $opt->id, 'device_type' => ['desktop', 'mobile', 'tablet'][rand(0,2)]]);
            }
        }

        // Topic 2: Coffee
        $topic2 = Topic::create([
            'title' => 'What is Your Favorite Coffee Brand?',
            'description' => 'Vote for your daily coffee choice.',
            'created_by' => $admin->id,
            'status' => 'active',
            'allow_multiple_votes' => true,
            'show_results_before_voting' => true,
            'opens_at' => Carbon::now()->subDays(7),
            'closes_at' => Carbon::now()->addDays(2),
            'total_votes' => 60
        ]);

        $options2 = [
            'Starbucks Coffee' => 30,
            'Nespresso' => 10,
            'Local Coffee' => 15,
            'Costa Coffee' => 5
        ];

        foreach ($options2 as $label => $votes) {
            $opt = Option::create(['topic_id' => $topic2->id, 'label' => $label, 'votes_count' => $votes]);
            $shuffledUsers = clone $users;
            $shuffledUsers = $shuffledUsers->shuffle();
            for ($i=0; $i<$votes; $i++) {
                $user = $shuffledUsers->pop();
                Vote::create(['user_id' => $user->id, 'topic_id' => $topic2->id, 'option_id' => $opt->id, 'device_type' => ['desktop', 'mobile', 'tablet'][rand(0,2)]]);
            }
        }

        // Topic 3: Programming
        $topic3 = Topic::create([
            'title' => 'Which Programming Language Do You Prefer?',
            'description' => 'For backend and frontend development.',
            'created_by' => $admin->id,
            'status' => 'active',
            'allow_multiple_votes' => false,
            'show_results_before_voting' => false, // false as requested in before vote UI
            'opens_at' => Carbon::now()->subDays(3),
            'closes_at' => Carbon::now()->addDays(4),
            'total_votes' => 35
        ]);

        $options3 = [
            'Python' => 15,
            'JavaScript' => 10,
            'Java' => 5,
            'C++' => 3,
            'Go' => 2
        ];

        foreach ($options3 as $label => $votes) {
            $opt = Option::create(['topic_id' => $topic3->id, 'label' => $label, 'votes_count' => $votes]);
            $shuffledUsers = clone $users;
            $shuffledUsers = $shuffledUsers->shuffle();
            for ($i=0; $i<$votes; $i++) {
                $user = $shuffledUsers->pop();
                Vote::create(['user_id' => $user->id, 'topic_id' => $topic3->id, 'option_id' => $opt->id, 'device_type' => ['desktop', 'mobile', 'tablet'][rand(0,2)]]);
            }
        }
    }
}
