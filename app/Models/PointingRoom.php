<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointingRoom extends Model
{
    protected $fillable = ['name', 'team_id'];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
