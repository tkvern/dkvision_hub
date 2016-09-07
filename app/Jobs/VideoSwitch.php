<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Task;
use App\Utils\DkvideoHelper;

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
        $videoDir = $payload['video_dir'];
        $snDir = DkvideoHelper::evalSerialNumberDir($videoDir);
        $videoBasename = basename($videoDir);
        $outputDir = dirname($payload['video_dir'], 2)."/output/$videoBasename";
        $ringRectifyFile = "/data/config/$snDir/ring_rectify.xml";
        $cameraModelFile = "/data/config/$snDir/camera_setting.xml";
        $endFrame = $payload['end_frame'];
        $startFrames = DkvideoHelper::computeStartFrames($payload['start_frame'], $payload['time_alignment']);
        $startFrames = implode("-", $startFrames);
        $cmdFormat = "../../build/bin/test_render_stereo_panorama ".
                      "-uuid %s ".
                      "-video_dir %s ".
                      "-output_dir %s ".
                      "-ring_rectify_file %s ".
                      "-camera_model_file %s ".
                      "-start_frames %s ".
                      "-end_frame %s";
        $cmd = printf($cmdFormat, $uuid, $videoDir, $outputDir, $ringRectifyFile,
                        $cameraModelFile, $startFrames, $endFrame);
        return $cmd;
    }

    private function updateTaskStatus($status) {
        $this->task->status = $status;
        $this->task->save();
    }
}