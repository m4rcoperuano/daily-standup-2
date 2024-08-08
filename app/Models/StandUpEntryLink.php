<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StandUpEntryLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'host',
        'text',
        'attributes',
    ];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function standUpEntry(): BelongsTo
    {
        return $this->belongsTo(StandUpEntry::class);
    }
}
