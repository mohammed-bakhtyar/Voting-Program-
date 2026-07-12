<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Topic;
use App\Models\Option;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotingTest extends TestCase
{
    use RefreshDatabase;

    public function test_voter_cannot_reach_admin_panel()
    {
        $voter = User::factory()->create(['is_admin' => false]);
        $response = $this->actingAs($voter)->get('/admin/topics');
        $response->assertStatus(403);
    }

    public function test_user_cannot_vote_twice()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();

        $admin = User::factory()->create(['is_admin' => true]);
        $topic = Topic::create([
            'title' => 'Test',
            'description' => 'Test',
            'opens_at' => now()->subDay(),
            'closes_at' => now()->addDay(),
            'created_by' => $admin->id,
        ]);
        $option1 = Option::create(['topic_id' => $topic->id, 'label' => 'Opt 1']);
        $option2 = Option::create(['topic_id' => $topic->id, 'label' => 'Opt 2']);

        $this->actingAs($user)->post("/topics/{$topic->id}/vote", ['option_id' => $option1->id]);
        $this->actingAs($user)->post("/topics/{$topic->id}/vote", ['option_id' => $option2->id]);

        $this->assertEquals(1, $topic->votes()->where('user_id', $user->id)->count());
        $this->assertEquals($option2->id, $topic->votes()->first()->option_id);
    }

    public function test_vote_before_opens_at_is_rejected()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();

        $admin = User::factory()->create(['is_admin' => true]);
        $topic = Topic::create([
            'title' => 'Test',
            'description' => 'Test',
            'opens_at' => now()->addDay(),
            'closes_at' => now()->addDays(2),
            'created_by' => $admin->id,
        ]);
        $option1 = Option::create(['topic_id' => $topic->id, 'label' => 'Opt 1']);

        $response = $this->actingAs($user)->post("/topics/{$topic->id}/vote", ['option_id' => $option1->id]);
        $response->assertStatus(403);
    }

    public function test_closed_topic_rejects_votes()
    {
        $user = User::factory()->create();
        $user->markEmailAsVerified();

        $admin = User::factory()->create(['is_admin' => true]);
        $topic = Topic::create([
            'title' => 'Test',
            'description' => 'Test',
            'opens_at' => now()->subDays(2),
            'closes_at' => now()->addDays(2),
            'closed_at' => now()->subDay(),
            'created_by' => $admin->id,
        ]);
        $option1 = Option::create(['topic_id' => $topic->id, 'label' => 'Opt 1']);

        $response = $this->actingAs($user)->post("/topics/{$topic->id}/vote", ['option_id' => $option1->id]);
        $response->assertStatus(403);
    }
}
