<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['topic_id', 'label', 'display_order', 'is_vip', 'votes_count'];

    protected $casts = [
        'is_vip' => 'boolean',
    ];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
