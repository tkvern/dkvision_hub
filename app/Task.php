<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    const WAITING = 1;
    const RUNNING = 2;
    const FINISH = 3;
    const ERROR = 4;
    const UNKNOWN = 5;

    public function user() {
        $this->belongsTo('App\User', 'creator_id');
    }
}
