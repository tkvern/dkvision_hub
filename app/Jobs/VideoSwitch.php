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
        system($cmd, $exit_code);
        if($exit_code === 0) {
            $this->updateTaskStatus(Task::FINISH);
        } else {
            $this->updateTaskStatus(Task::ERROR);
        }
    }

    private function generateCmdFromTask() {
        $uuid = $this->task['uuid'];
        $payload = json_decode($this->task['payload']);
        $videoDir = $payload['video_dir'];
        $snDir = DkvideoHelper::evalSerialNumberDir($videoDir);
        $outputDir = DkvideoHelper::getOutputDir($videoDir);
        $ringRectifyFile = "/data/config/$snDir/ring_rectify.xml";
        $cameraSettingFile = "/data/config/$snDir/camera_setting.xml";
        $topRectifyFile = "/data/config/$snDir/top_rectify.xml";
        $bottomRectifyFile = "/data/config/$snDir/bottom_rectify.xml";
        $mixRectifyFile = "/data/config/$snDir/mix_rectify.xml";
        $enableTop = $payload['enable_top'];
        $enableBottom = $payload['enable_bottom'];
        $enableColorAdjust = $payload['enable_coloradjust'];
        $endFrame = $payload['end_frame'];
        $startFrames = DkvideoHelper::computeStartFrames($payload['start_frame'], $payload['time_alignment']);
        $startFrames = implode("_", $startFrames);
        $cmdFormat = "../../build/bin/test_render_stereo_panorama ".
                      "-uuid %s ".
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
        $cmd = printf($cmdFormat, $uuid, $videoDir, $outputDir,
                      $ringRectifyFile, $topRectifyFile, $bottomRectifyFile, $mixRectifyFile, $cameraSettingFile,
                      $enableTop, $enableBottom, $enableColorAdjust, $startFrames, $endFrame);
        return $cmd;
    }

    private function updateTaskStatus($status) {
        $this->task->status = $status;
        $this->task->save();
    }
}