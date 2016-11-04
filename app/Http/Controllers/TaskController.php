<?php

namespace App\Http\Controllers;

use App\Jobs\VideoSwitch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use DB;

use App\Http\Requests;
use App\Task;

class TaskController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $filterKeys = [
            'all' => $request->query('all'), 
            'status' => $request->query('status'), 
            'key' => $request->query('key')
        ];
        $resource = Task::query();
        if (!is_true($filterKeys['all'])) {
            $resource = $resource->where('creator_id', Auth::user()->id);
        } 
        if (!empty($filterKeys['status'])) {
            $resource = $resource->where('status', $filterKeys['status']);
        }
        if (!empty($filterKeys['key'])) {
            $resource = $resource->where('title', 'like', "%{$filterKeys['key']}%");
        }
        $tasks = $resource->with('creator')
                          ->with('attachedTasks')
                          ->where('parent_id', 0)
                          ->orderBy('id', 'desc')
                          ->paginate(10);
        return view('task.index', ['tasks' => $tasks, 'filterKeys' => $filterKeys]);
    }

    public function create() {
        return view('task.new');
    }

    public function store(Request $request) {
        $this->validator($request)->validate();
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
        $this->authorize('delete', $task);
        if (!$task->canDelete()) {
            return errorJsonResponse('400.003', '当前状态不允许删除');
        }
        if($task->subTasks()->count() > 0) {
            $task->subTasks()->delete();
        }
        $task->delete();
        return $this->successJsonResponse();
    }

    public function retry($task_id) {
        $task = Task::find($task_id);
        $this->authorize('retry', $task);
        $count = $task->subTasks()->count();
        if (!$task->canRetry()) {
            return $this->errorJsonResponse('400.002', '当前状态不允许重试');
        }
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
        return $this->successJsonResponse();
}

    public function terminate($task_id) {
        $task = Task::find($task_id);
        $this->authorize('terminate', $task);
        if (!$task->canTerminate()) {
            return $this->errorJsonResponse('400.001', '当前状态不允许终止');
        }
        $bin = basename($task->execPath());
        $ansible_cmd = "sudo -Hu ansible /usr/local/bin/ansible {$task->exec_ip} ".
                       "-a 'pkill -nfu visiondk $bin' -b --become-user=visiondk 2>&1";
        info("exec: $ansible_cmd");
        exec($ansible_cmd, $output, $ret);
        if ($ret != 0) {
            return $this->errorJsonResponse('500.001', implode('\n', $output));
        } else {
            return $this->successJsonResponse();
        }
    }

    public function createTopBottom(Request $request, $task_id) {
        $task = Task::find($task_id); 
        if (!$task->canCreateTopAndBottom()) {
            return $this->errorJsonResponse('400.004', '任务类型不支持或状态不允许');
        }
        $payload = array_copy($task->payload);
        $newTask = $task->replicate();
        $newTask->uuid = uuid1();
        $newTask->attach_id = $task->id;
        $newTask->task_type = "TOP_BOTTOM";
        $newTask->status = Task::WAITING;
        $newTask->exec_ip = "";
        $newTask->payload = array_copy($task->payload);
        $payload['task_type']= 'TOP_BOTTOM';
        $payload['enable_top']= $request->input('enable_top', '0');
        $payload['enable_bottom']= $request->input('enable_bottom', '0');
        $payload['output_dir'] = $task->outputDir();
        $newTask->payload = $payload;
        $newTask->save();
        $this->enQueueTasks([$newTask]);
        return $this->successJsonResponse();
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
            $task->task_type = $task_type;
            $task->payload = $payload;
            $task->creator_id = Auth::user()->id;
            $task->parent_id = 0;
            $task->status = Task::WAITING;
            $task->priority = intval($input['priority']);
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

    private function validator($request) {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "payload.video_dir" => "required",
            "payload.output_dir" => "required",
            "payload.start_frame" => "required|integer",
            "payload.end_frame" => "required|integer",
            "payload.enable_coloradjust" => "required",
            "payload.quality" => "required",
            "payload.camera_type" => "required",
            "priority" => "required|integer",
            "task_types" => "required|array"
        ]);
        $validator->after(function($validator) use ($request) {
            if(!file_exists($request->input('payload.video_dir'))) {
                $validator->errors()->add('payload.video', '视频路径不存在');
            }
        });
        return $validator;
    }
}
