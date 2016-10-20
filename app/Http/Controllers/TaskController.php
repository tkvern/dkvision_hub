<?php

namespace App\Http\Controllers;

use App\Jobs\VideoSwitch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Http\Requests;
use App\Task;

class TaskController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        if(is_true($request->query('all'))) {
            $resource = Task::query();
        } else {
            $resource = Auth::user()->tasks();
        }
        $tasks = $resource->where('parent_id', 0)->orderBy('id', 'desc')->paginate(10);
        return view('task.index', ['tasks' => $tasks, 'all' => $request->query('all')]);
    }

    public function create() {
        return view('task.new');
    }

    public function store(Request $request) {
        $this->validate($request, [
            "title" => "required",
            "payload.video_dir" => "required",
            "payload.output_dir" => "required",
            "payload.start_frame" => "required|integer",
            "payload.end_frame" => "required|integer",
            "payload.enable_top" => "required",
            "payload.enable_bottom" => "required",
            "payload.enable_coloradjust" => "required",
            "payload.quality" => "required",
            "payload.camera_type" => "required",
            "task_types" => "required|array"
        ]);
        $tasks = $this->createTasks($request->input());
        $this->enQueueTasks($tasks);
        return redirect()->action('TaskController@index');
    }

    public function show($task_id) {
        $task = Task::find($task_id);
        $statistics = DB::table('tasks')
                        ->select('status', DB::raw('count(*) as status_count'))
                        ->where('parent_id', $task_id)
                        ->groupBy('status')
                        ->get();
        $counts = ['all' => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($statistics as $stat) {
            $counts[$stat->status] = $stat->status_count;
            $counts['all'] += $stat->status_count;
        }
        return view('task.show', ['task' => $task, 'counts' => $counts]);
    }

    public function destroy($task_id) {
        $task = Task::find($task_id);
        if($task->creator_id !== auth()->user()->id) {
            return response()->json([
                'err_code' => '100',
                'err_msg' => '没有权限'
            ]);
        }
        if($task->subTasks()->count() > 0) {
            $task->subTasks()->delete();
        }
        $task->delete();
        return response()->json([
            'err_code' => '0',
            'err_msg' => 'SUCCESS'
        ]);
    }

    public function retry($task_id) {
        $task = Task::find($task_id);
        if($task->creator_id !== auth()->user()->id) {
            return response()->json([
                'err_code' => '100',
                'err_msg' => '没有权限'
            ]);
        }
        $count = $task->subTasks()->count();
        if($count === 0) {
            $task->status = Task::WAITING;
            $task->processed_at = null;
            $task->save();
            dispatch((new VideoSwitch($task))->onQueue('videos'));
        } else {
            $subTasks = $task->subTasks()->get();
            foreach ($subTasks as $_task) {
                $_task->status = Task::WAITING;
                $_task->save();
                dispatch((new VideoSwitch($_task))->onQueue('videos'));
            }
        }
        return response()->json([
            'err_code' => '0',
            'err_msg' => 'SUCCESS'
        ]);
    }

    private function createTasks($input) {
        $tasks = [];
        $task_types = array_reverse($input['task_types']);
        $payload = $input['payload'];
        $payload['video_dir'] = trim($payload['video_dir']);
        $payload['output_dir'] = trim($payload['output_dir']);
        foreach($task_types as $task_type) {
            $payload = array_copy($payload);
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
