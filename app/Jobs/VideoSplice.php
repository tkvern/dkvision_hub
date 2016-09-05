<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Task;

class VideoSplice implements ShouldQueue
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
        $cmd = $this->generateCmdFromTask();
        system($cmd, $return_code);
        if($return_code === 0) {
            $this->updateTaskStatus(Task::FINISH);
        } else {
            $this->updateTaskStatus(Task::ERROR);
        }
    }

    private function generateCmdFromTask() {
        $uuid = $this->task['uuid'];
        $payload = json_decode($this->task['payload'];
        $video_dir = $payload['video_dir'];
        $output_dir = $payload['video_dir'];
        $ring_rectify_file = "";
        $camera_model_file = "";
        $end_frame = $payload['end_frame'];
        $start_frames = $this->compute_start_frames( $payload['start_frame'], $payload['time_alignment']);
        $start_frames = implode("-", $start_frames);
        $cmd_format = "../../build/bin/test_render_stereo_panorama ".
                      "-uuid %s ".
                      "-video_dir %s ".
                      "-output_dir %s ".
                      "-ring_rectify_file %s ".
                      "-camera_model_file %s ".
                      "-start_frames %s ".
                      "-end_frame %s";
        $cmd = printf($cmd_format, $uuid, $video_dir, $output_dir, $ring_rectify_file,
                        $camera_model_file, $start_frames, $end_frame);
        return $cmd;
    }

    private function compute_start_frames($start_frame, $time_alignment) {
        $start_frames = [];
        foreach ($time_alignment as  $value) {
            $start_frame +=  $value;
            $start_frames[] = $start_frame;
        }
        return $start_frames;
    }

    private function updateTaskStatus($status) {
        $this->task->status = $status;
        $this->task->save();
    }
}