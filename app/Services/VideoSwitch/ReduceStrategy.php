<?php
/**
 * Created by PhpStorm.
 * User: liujun
 * Date: 16/9/7
 * Time: 下午11:34
 */
namespace App\Services\VideoSwitch;

use Illuminate\Support\Facades\DB;

use App\Contacts\VideoSwitch\Strategy;
use App\Jobs\VideoSwitch;

class ReduceStrategy implements  Strategy {
    public function handle($task)
    {
        $subTasks = $this->mapTask($task);
        array_map(function($subTask) {
            dispatch((new VideoSwitch($subTask))->onQueue('videos'));
        }, $subTasks);
    }

    /**
     * @param  App\Task $task
     * @return array
     */
    protected function mapTask($task) {
        $payload = $task->payload;
        $start_frame = $payload['start_frame'];
        $end_frame = $payload['end_frame'];
        $total_frame = $end_frame - $start_frame;
        $subTasks = [];
        if($total_frame > 5) {
            for($i = $start_frame; $i < $end_frame; $i += 5) {
                $tmpPayload = array_copy($payload);
                $tmpPayload['start_frame'] = $i;
                $tmpPayload['end_frame'] = $i + 5 > $end_frame ? $end_frame : $$i + 1;
                $subTask = $task->replicate(['uuid', 'payload', 'parent_id']);
                $subTask->uuid = uuid1();
                $subTask->payload = $tmpPayload;
                $subTask->parent_id = $task->id;
                $subTasks[] = $subTask;
            }
            transaction_save_many($subTasks);
        } else {
            $subTasks[] = $task;
        }
        return $subTasks;
    }
}