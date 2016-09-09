<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Task;
use App\Utils\DkvideoHelper;

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
        $cmd = $this->generateCmdFromTask();
        info("exec: $cmd");
        system($cmd, $exit_code);
        if($exit_code === 0) {
            $this->updateTaskStatus(Task::FINISH);
        } else {
            $this->updateTaskStatus(Task::ERROR);
        }
    }

    private function generateCmdFromTask() {
        $uuid = $this->task['uuid'];
        $payload = $this->task['payload'];
        $videoDir = $payload['video_dir'];
        $outputDir = $this->task->outputDir();
        if(! file_exists($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        $snDir = $this->task->configDir();
        $ringRectifyFile = "$snDir/ring_rectify.xml";
        $cameraSettingFile = "$snDir/camera_setting.xml";
        $topRectifyFile = "$snDir/top_rectify.xml";
        $bottomRectifyFile = "$snDir/bottom_rectify.xml";
        $mixRectifyFile = "$snDir/mix_rectify.xml";
        $enableTop = $payload['enable_top'];
        $enableBottom = $payload['enable_bottom'];
        $enableColorAdjust = $payload['enable_coloradjust'];
        $endFrame = $payload['end_frame'];
        $startFrames = implode("_", $this->task->startFrames());
        $cmdFormat = "../../build/bin/test_render_stereo_panorama ".
                      "-task_uuid %s ".
                      "-video_dir %s ".
                      "-output_dir %s ".
                      "-ring_rectify_file %s ".
                      "-top_rectify_file %s ".
                      "-bottom_rectify_file %s ".
                      "-mix_rectify_file %s ".
                      "-camera_setting_file %s ".
                      "-enable_top %s ".
                      "-enable_bottom %s ".
                      "-enable_coloradjust %s ".
                      "-start_frames %s ".
                      "-end_frame %s";
        $cmd = sprintf($cmdFormat, $uuid, $videoDir, $outputDir,
                      $ringRectifyFile, $topRectifyFile, $bottomRectifyFile, $mixRectifyFile, $cameraSettingFile,
                      $enableTop, $enableBottom, $enableColorAdjust, $startFrames, $endFrame);
        return $cmd;
    }

    private function updateTaskStatus($status) {
        $this->task->status = $status;
        $this->task->save();
    }
}