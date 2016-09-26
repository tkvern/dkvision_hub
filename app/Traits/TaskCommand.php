<?php
/**
 * Created by PhpStorm.
 * User: liujun
 * Date: 2016/9/23
 * Time: 下午3:33
 */
namespace  App\Traits;

use App\Utils\DkvideoHelper;

Trait TaskCommand {
    public function outputDir() {
        $outputDir = DkvideoHelper::getOutputDir($this->payload['video_dir']);
        if($this->parent_id === 0) {
            $outputDir  = join_paths($outputDir, $this->uuid);
        } else {
            $outputDir = join_paths($outputDir, $this->parentTask()->first()->uuid);
        }
        return $outputDir;
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

    private function execPath() {
        $execPath = '';
        switch ($this->payload['task_type']) {
            case '3D':
                $execPath = 'test_3d';
                break;
            case '3D_Fast':
                $execPath = 'test_3d_fast';
                break;
            case '2D':
                $execPath = 'test_2d';
                break;
            case '2D_Fast':
                $execPath = 'test_2d_fast';
                break;
        }
        $execPath = join_paths(config('task.bin_path'), $execPath);
        return $execPath;
    }

    private function allCmdParameters()
    {
        $paramsArr = [];
        $payload = $this->payload;
        $paramsArr['video_dir'] = $payload['video_dir'];
        $paramsArr['output_dir'] = $this->outputDir();
        $snDir = join_paths($payload['video_dir'], 'config');
        if(! file_exists($snDir)) {
            $snDir = $this->configDir();
        }
        $paramsArr['ring_rectify_file'] = "$snDir/ring_rectify.xml";
        $paramsArr['camera_setting_file'] = "$snDir/camera_setting.xml";
        $paramsArr['top_rectify_file'] = "$snDir/top_rectify.xml";
        $paramsArr['bottom_rectify_file'] = "$snDir/bottom_rectify.xml";
        $paramsArr['mix_rectify_file'] = "$snDir/mix_rectify.xml";
        $paramsArr['enable_top'] = $payload['enable_top'];
        $paramsArr['enable_bottom'] = $payload['enable_bottom'];
        $paramsArr['enable_coloradjust'] = $payload['enable_coloradjust'];
        $paramsArr['start_frame'] = $payload['start_frame'];
        $paramsArr['end_frame'] = $payload['end_frame'];
        $paramsArr['time_alignment'] = implode('_', $payload['time_alignment']);
        return $paramsArr;
    }

    private function cmdRequiredParameters() {
        $parameters = [];
        switch($this->payload['task_type']) {
            case '3D':
            case '3D_Fast':
                $parameters = [
                    'video_dir', 'output_dir', 'ring_rectify_file', 'top_rectify_file', 'bottom_rectify_file',
                    'mix_rectify_file', 'camera_setting_file', 'enable_top', 'enable_bottom', 'enable_coloradjust',
                    'start_frame', 'end_frame', 'time_alignment'
                ];
                break;
            case '2D':
                $parameters = [
                    'video_dir', 'output_dir', 'ring_rectify_file', 'mix_rectify_file', 'camera_setting_file',
                    'enable_top', 'start_frame', 'end_frame', 'time_alignment'
                ];
                break;
            case '2D_Fast':
                $parameters = [
                    'video_dir', 'output_dir', 'ring_rectify_file', 'camera_setting_file',
                    'start_frame', 'end_frame', 'time_alignment'
                ];
                break;
        }
        return $parameters;
    }
}