<?php

namespace App\Models;

use App\Observers\StandUpEntryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy(StandUpEntryObserver::class)]
class StandUpEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'in_progress',
        'priorities',
        'blockers',
    ];

    protected $casts = [
        'date' => 'datetime'
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
