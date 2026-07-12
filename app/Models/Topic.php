<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['title', 'description', 'status', 'allow_multiple_votes', 'show_results_before_voting', 'opens_at', 'closes_at', 'closed_at', 'created_by'])]
class Topic extends Model
{
    protected function casts(): array
    {
        return [
            'opens_at' => 'datetime',
            'closes_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getIsClosedAttribute(): bool
    {
        return $this->status === 'closed' || $this->closed_at !== null || now()->isAfter($this->closes_at);
    }
}
