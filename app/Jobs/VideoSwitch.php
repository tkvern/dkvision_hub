<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Task;
use League\Flysystem\Exception;

class VideoSwitch implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $task;

    /**
     * Create a new job instance.
     *
     * @param Task $task
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->task->status !== Task::WAITING) {
            return;
        }
        $this->updateTaskStatus(Task::RUNNING);
        $cmd = $this->task->cmdString();
        info("exec: $cmd");
        system($cmd, $exit_code);
        if($exit_code === 0) {
            $this->updateTaskStatus(Task::FINISH);
        } else {
            $this->updateTaskStatus(Task::ERROR);
        }
    }

    public function failed(Exception $e) {
        $this->updateTaskStatus(Task::ERROR);
    }

    private function updateTaskStatus($status) {
        $this->task->status = $status;
        $this->task->processed = 100;
        $this->task->save();
        if ($this->task->parent_id !== 0) {
            $this->updateParentStatus();
        }
    }

    private function updateParentStatus() {
        $parentTask = $this->task->parentTask()->first();
        if($this->task->status !== Task::ERROR && $this->task->staus !== Task::FINISH) {
            if($this->task->status === Task::RUNNING && $parentTask->status !== Task::RUNNING) {
                $parentTask->status = Task::RUNNING;
                $parentTask->save();
                return;
            }
        }
        $subTasks = $parentTask->subTasks()->get();
        $finished = 0;
        $failed = 0;
        $running = 0;
        foreach($subTasks as $task) {
            if($task->status === Task::FINISH) {
                $finished += 1;
            } elseif($task->status === Task::ERROR) {
                $failed += 1;
            } else {
                $running += 1;
            }
        }
        if(count($subTasks) === $finished) {
            $parentTask->status = Task::FINISH;
            $parentTask->save();
        } elseif(count($subTasks) === $failed + $finished) {
            $parentTask->status = Task::ERROR;
            $parentTask->save();
        }
    }
}
