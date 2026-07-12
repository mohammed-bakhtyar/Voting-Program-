<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TopicPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Topic $topic): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Topic $topic): bool
    {
        return $user->is_admin && $topic->votes()->count() === 0;
    }

    public function delete(User $user, Topic $topic): bool
    {
        return $user->is_admin && $topic->votes()->count() === 0;
    }

    public function vote(User $user, Topic $topic): bool
    {
        return now()->between($topic->opens_at, $topic->closes_at) && is_null($topic->closed_at);
    }
}
