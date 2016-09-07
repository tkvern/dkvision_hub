<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Task;

class TaskController extends Controller
{
    public function store(Request $request) {
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
        $this->createTasks($request->all());
    }

    private function createTasks($input) {
        $tasks = [];
        $task_types = $input['task_types'];
        foreach($task_types as $task_type) {
            $task = new Task();
            $this->uuid = "";
            $this->title = $input['title'].'_'.$task_type;
            $this->description = $input['description'];
            $task->task_type = $task_type;
            $task->payload = json_encode($input['payload']);
            $task->creator_id = Auth::user()->id;
            $task->save();
            $tasks[] = $task;
        }
        return $tasks;
    }

    public function index(){
        return view('task.index');
    }

    public function new() {
        return view('task.new');
    }
}
