<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    protected $fillable = ['user_id', 'guess_attempts', 'is_won'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
