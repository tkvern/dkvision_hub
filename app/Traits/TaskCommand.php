<?php
/**
 * Created by PhpStorm.
 * User: liujun
 * Date: 2016/9/23
 * Time: 下午3:33
 */
namespace  App\Traits;

use DirectoryIterator;
use App\Utils\DkvideoHelper;

Trait TaskCommand {
    public function outputDir() {
        if ($this->task_type === 'TOP_BOTTOM') {
            return $this->payload['output_dir'];
        } elseif (!empty($this->payload['output_dir'])) {
            return join_paths($this->payload['output_dir'], $this->payload['task_type']);
        } else {
            return join_paths($this->payload['video_dir'], $this->payload['task_type']);
        }
    }

    public function targetDir() {
        if ($this->task_type === 'TOP_BOTTOM') {
            if (is_true($this->payload['enable_top'])) {
                return join_paths($this->outputDir(), 'top_pano');
            } else {
                return join_paths($this->outputDir(), 'bottom_pano');
            }
        } else {
            return join_paths($this->outputDir(), 'left_pano');
        }
    }

    public function configDir() {
        $snDir = DkvideoHelper::evalSerialNumberDir($this->payload['video_dir']);
        $base = config('task.config_dir');
        return join_paths($base, $snDir);
    }

    public function cmdString() {
        $allParams = $this->allCmdParameters();
        $requiredParams = $this->cmdRequiredParameters();
        $cmd = [$this->execPath()];
        foreach ($requiredParams as $param) {
            $cmd[] = "-$param {$allParams[$param]}";
        }
        $cmd = implode(' ', $cmd);
        return $cmd;
    }

    public function execPath() {
        $execBins = [
            'VISIONDK_3D' => 'test_3d_visiondk',
            'VISIONDK_2D' => 'test_2d_visiondk',
            'FACEBOOK_3D' => 'test_3d_facebook',
            'FACEBOOK_2D' => 'test_2d_facebook',
            'PREVIEW' => 'test_preview',
            'TOP_BOTTOM' => 'test_top_and_bottom'
        ];
        $execBin = array_get($execBins, strtoupper($this->task_type));
        $execPath = join_paths(config('task.visiondk_bin'), $execBin);
        return $execPath;
    }

    private function allCmdParameters()
    {
        $paramsArr = [];
        $payload = $this->payload;
        $video_dir = $payload['video_dir'];
        $paramsArr['video_dir'] = $payload['video_dir'];
        $paramsArr['output_dir'] = $this->outputDir();
        $paramsArr['ring_rectify_file'] = join_paths($video_dir, 'ring_rectify.xml');
        $paramsArr['top_rectify_file'] = join_paths($video_dir, 'top_rectify.xml');
        $paramsArr['bottom_rectify_file'] = join_paths($video_dir, 'bottom_rectify.xml');
        $paramsArr['mix_rectify_file'] = join_paths($video_dir, 'mix_rectify.xml');
        $paramsArr['camera_setting_file'] = join_paths(config('task.visiondk_setting_path'), 
            DkvideoHelper::cameraSettingName($payload['camera_type'], $payload['task_type']));
        $paramsArr['enable_top'] = array_get($payload, 'enable_top', '0');
        $paramsArr['enable_bottom'] = array_get($payload, 'enable_bottom', '0');
        $paramsArr['enable_coloradjust'] = $payload['enable_coloradjust'];
        $paramsArr['start_frame'] = $this->findStartFrame();
        $paramsArr['end_frame'] = $payload['end_frame'];
        $paramsArr['time_alignment_file'] = join_paths($payload['video_dir'], 'time.txt');
        $paramsArr['save_type'] = DkvideoHelper::cameraSaveType($payload['camera_type']);
        return $paramsArr;
    }

    private function cmdRequiredParameters() {
        $parameters = [
            'video_dir', 'output_dir', 'time_alignment_file', 
            'camera_setting_file', 'ring_rectify_file', 'top_rectify_file', 'bottom_rectify_file',
            'mix_rectify_file', 'enable_top', 'enable_bottom', 'start_frame', 'end_frame', 
            'save_type'
        ];
        return $parameters;
    }

    private function findStartFrame() {
        if ($this->payload['task_type'] === 'PREVIEW') {
            return $this->payload['start_frame'];
        }
        $outputDir = $this->outputDir();
        $targetDir = $this->targetDir();
        info("target dir: $targetDir");
        if (!file_exists($targetDir)) {
            return $this->payload['start_frame'];
        }
        $dirIterator = new DirectoryIterator($targetDir);
        $lastFilename = '';
        foreach($dirIterator as $fileInfo) {
            if ($fileInfo->isFile()) {
                $filename = $fileInfo->getFilename();
                if (strpos($filename, '_') === false && $filename > $lastFilename) {
                    $lastFilename = $filename;
                }
            }
        }
        $number = explode('.', $lastFilename, 2)[0];
        if (preg_match('/^[0-9]+$/', $number)) {
            info("target frame: $number");
            return intval($number) - 1;
        } else {
            return intval($this->payload['start_frame']);
        }
    }
}