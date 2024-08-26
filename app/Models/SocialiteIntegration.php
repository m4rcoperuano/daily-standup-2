<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialiteIntegration extends Model
{
    use HasFactory;
    protected $fillable = [
        'provider',
        'access_token',
        'refresh_token',

        'provider_user_name',
        'provider_user_avatar',
        'provider_user_email',
        'provider_user_nick_name',
        'provider_user_id',

        'meta',
        'version',
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
