<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;

use App\Task;

class TaskController extends Controller
{
    public function index() {
        $tasks = Auth::user()->tasks()->where('parent_id', 0)->paginate(10);
        return view('task.index', ["tasks" => $tasks]);
    }

    public function show(Request $request, $task_id) {
        return view('task.show');
    }

    public function create() {
        return view('task.new');
    }

    public function store(Request $request) {
        $this->validate($request, [
            "title" => "required",
            "payload.video_dir" => "required",
            "payload.start_frame" => "required|integer",
            "payload.end_frame" => "required|integer",
            "payload.time_alignment" => "required|array",
            "payload.enable_top" => "required",
            "payload.enable_bottom" => "required",
            "payload.enable_coloradjust" => "required",
            "payload.quality" => "required",
            "task_types" => "required|array"
        ]);
        $tasks = $this->createTasks($request->input());
        $this->enQueueTasks($tasks);
        return redirect()->action('TaskController@index');
    }

    private function createTasks($input) {
        $tasks = [];
        $task_types = $input['task_types'];
        foreach($task_types as $task_type) {
            $payload = array_copy($input['payload']);
            $payload['task_type'] = $task_type;
            $task = new Task();
            $task->uuid = uuid1();
            $task->title = $input['title'];
            $task->description = $input['description'];
            $task->task_type = 'videoswitch';
            $task->payload = $payload;
            $task->creator_id = Auth::user()->id;
            $task->parent_id = 0;
            $task->status = Task::WAITING;
            $tasks[] = $task;
        }
        transaction_save_many($tasks);
        return $tasks;
    }

    private function enQueueTasks($tasks) {
        array_map(function($task) {
            app()->make('App\Contacts\VideoSwitch\Strategy')->handle($task);
        }, $tasks);
    }
}
