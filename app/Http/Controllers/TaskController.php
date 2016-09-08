<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Task;
use App\Utils\DkvideoHelper;

class TaskController extends Controller
{
    public function index() {
        return view('task.index');
    }

    public function show(Request $request, $task_id) {
        return view('task.show');
    }

    public function new() {
        return view('task.new');
    }

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
        //$this->makeOutputDir($this->input('payload.video_dir'));
        $tasks = $this->createTasks($request->all());
        $this->enQueueTasks($tasks);
        return redirect()->route('/tasks');
    }

    private function createTasks($input) {
        $tasks = [];
        $task_types = $input['task_types'];
        foreach($task_types as $task_type) {
            $task = new Task();
            $this->uuid = uuid1();
            $this->title = $input['title'].'_'.$task_type;
            $this->description = $input['description'];
            $task->task_type = $task_type;
            $task->payload = json_encode($input['payload']);
            $task->creator_id = Auth::user()->id;
            $tasks[] = $task;
        }
        DB::transaction(function() use ($tasks) {
            $tasks.map(function($task) {
               $task->save();
            });
        });
        return $tasks;
    }

    private function enQueueTasks($tasks) {
        array_map(function($task) {
            app()->make(App\Contacts\VideoSwitch\Strategy::class)->handle($task);
        }, $tasks);
    }

    /**
     *  创建对应的输出文件夹
     *
     * @param string $input_dir
     */
    private function makeOutputDir($input_dir) {
        $outputDir = DkvideoHelper::getOutputDir($this->input('payload.video_dir'));
        if(!file_exists($outputDir)) {
            mkdir($outputDir);
        }
    }
}
