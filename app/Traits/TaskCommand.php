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
        if (!empty($payload['output_dir'])) {
            return $payload['output_dir'];
        }
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
        $execBin = '';
        switch ($this->payload['task_type']) {
            case 'VISIONDK_3D':
                $execBin = 'test_3d_visiondk';
                break;
            case 'VISIONDK_2D':
                $execBin = 'test_2d_visiondk';
                break;
            case 'FACEBOOK_3D':
                $execBin = 'test_3d_facebook';
                break;
            case 'FACEBOOK_2D':
                $execBin = 'test_2d_facebook';
                break;
            default:
                $execBin = 'test_preview';
                break;
        }
        $execPath = join_paths(config('task.bin_path'), $execBin);
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
        $paramsArr['camera_ring_radius'] = DkvideoHelper::cameraRingRadius($payload['camera_type'],
                                             $payload['task_type']);
        $paramsArr['save_type'] = DkvideoHelper::cameraSaveType($payload['camera_type']);
        return $paramsArr;
    }

    private function cmdRequiredParameters() {
        $parameters = [];
        switch(strtoupper($this->payload['task_type'])) {
            case 'FACEBOOK_3D':
            case 'FACEBOOK_2D':
                $parameters = [
                    'video_dir', 'output_dir', 'ring_rectify_file', 'camera_setting_file',
                    'enable_top', 'enable_bottom', 'enable_coloradjust','start_frame', 
                    'end_frame', 'time_alignment', 'camera_ring_radius', 'save_type'
                ];
                break;
            case 'VISIONDK_3D':
            case 'VISIONDK_2D':
                $parameters = [
                    'video_dir', 'output_dir', 'ring_rectify_file', 'camera_setting_file',
                    'enable_top', 'enable_bottom', 'enable_coloradjust', 'start_frame', 
                    'end_frame', 'time_alignment', 'save_type'
                ];
                break;
            default:
                $parameters = [
                    'video_dir', 'output_dir', 'ring_rectify_file', 'camera_setting_file',
                    'start_frame', 'end_frame', 'time_alignment'
                ];
        }
        return $parameters;
    }
}