<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandUpEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'in_progress',
        'priorities',
        'blockers',
    ];

    public function standUpGroup(): BelongsTo
    {
        return $this->belongsTo(StandUpGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
