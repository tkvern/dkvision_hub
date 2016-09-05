<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Task;

class Task extends Controller
{
    public function create(Request $request) {
        $this->validate($request, [
            "title" => "required",
            "payload.video_dir" => "required",
            "payload.start_frame" => "required|integer",
            "payload.end_frame" => "required|integer",
            "payload.time_alignment" => "required",
            "payload.enable_top" => "required",
            "payload.enable_bottom" => "required",
            "payload.quality" => "required",
            "task_types" => "required|array"
        ]);
    }

    private function createTasksByInput($input) {
        $tasks_types = $input['task_types'];
        foreach($tasks_types as $tasks_type) {
            $task = new Task();
            $task->save();
        }
    }
}
