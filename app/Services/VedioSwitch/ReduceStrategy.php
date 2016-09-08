<?php
/**
 * Created by PhpStorm.
 * User: liujun
 * Date: 16/9/7
 * Time: 下午11:34
 */
namespace App\Tasks\VideoSwitch;

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
        $payload = json_decode($task->payload);
        $start_frame = $payload['start_frame'];
        $end_frame = $payload['end_frame'];
        $total_frame = $end_frame - $start_frame;
        $subTasks = [];
        if($total_frame > 5) {
            for($i = $start_frame; $i < $end_frame; $i += 5) {
                $tmpPayload = clone($payload);
                $tmpPayload['start_frame'] = $i;
                $tmpPayload['end_frame'] = $i + 5 > $end_frame ? $end_frame : $$i + 1;
                $subTask = $task->clone();
                $subTask->uuid = uuid1();
                $subTask->payload = json_encode($tmpPayload);
                $subTask->parent_id = $task->id;
                $subTasks[] = $subTask;
            }
            DB::transcation(function() use ($subTasks) {
                array_map(function($subTask) {
                    $subTask->save();
                }, $subTasks);
            });
        } else {
            $subTasks[] = $task;
        }
        return $subTasks;
    }
}