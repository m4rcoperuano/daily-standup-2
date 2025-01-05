<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['pointing_room_id', 'user_id', 'value'];

    public function pointingRoom()
    {
        return $this->belongsTo(PointingRoom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
