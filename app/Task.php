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
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function subTasks() {
        return $this->hasMany('App\Task', 'parent_id');
    }

    public function parentTask() {
        return $this->belongsTo('App\Task', 'parent_id');
    }
}
