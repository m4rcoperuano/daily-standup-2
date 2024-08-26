<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StandUpGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'atlassian_sprint_id',
        'atlassian_board_id'
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function standUpEntries(): HasMany
    {
        return $this->hasMany(StandUpEntry::class);
    }
}
