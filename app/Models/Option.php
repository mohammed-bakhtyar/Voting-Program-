<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['topic_id', 'label', 'order'])]
class Option extends Model
{
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
