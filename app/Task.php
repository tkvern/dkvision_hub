<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Utils\DkvideoHelper;

class Task extends Model
{
    const WAITING = 1;
    const RUNNING = 2;
    const FINISH = 3;
    const ERROR = 4;
    const UNKNOWN = 5;

    protected $dates = [
        'created_at',
        'updated_at',
        'processed_at'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    private $_process;

    public function user() {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function subTasks() {
        return $this->hasMany('App\Task', 'parent_id');
    }

    public function parentTask() {
        return $this->belongsTo('App\Task', 'parent_id');
    }

    public function human_status() {
        static $human_readings = [
            Task::WAITING => "等待中",
            Task::RUNNING => "运行中",
            Task::FINISH => "完成",
            Task::ERROR => "失败",
            Task::UNKNOWN => "未知"
        ];
        return array_get($human_readings, $this->status, "未知");
    }

    public function status_class() {
        static $status_classes = [
            Task::WAITING => "primary",
            Task::RUNNING => "info",
            Task::FINISH => "success",
            Task::ERROR => "danger",
            Task::UNKNOWN => "warning"
        ];
        return array_get($status_classes, $this->status, "warning");
    }

    public function status_label() {
        $status_class = $this->status_class();
        $label_content = $this->human_status();
        return "<span class='label label-$status_class'>$label_content</span>";
    }

    public function processing() {
        if(! is_null($this->_process)) {
            return $this->_process;
        }
        if($this->status === self::FINISH) {
            return $this->_process = 100;
        }
        if($this->status === self::WAITING) {
            return $this->_process = 0;
        }
        if($this->status === self::ERROR && ! is_null($this->processed_at)) {
            return $this->_process = $this->processed;
        }
        if(! is_null($this->processed_at) && Carbon::now()->diffInSeconds($this->processed_at) < 60) {
            return $this->_process = $this->processed;
        }
        $this->processed = $this->calcProcessing();
        $this->processed_at = Carbon::now();
        $this->save();
        return $this->_process = $this->processed;
    }

    public function calcProcessing() {
        $totalFrames = $this->payload['end_frame'] - $this->payload['start_frame'] + 1;
        $targetDir = $this->outputDir()."/left_pano";
        if (! file_exists($targetDir)) {
            return 0;
        }
        $finishFrames = directory_file_count($targetDir);
        return floor($finishFrames*100/$totalFrames);
    }


    public function outputDir() {
        $outputDir = DkvideoHelper::getOutputDir($this->payload['video_dir']);
        if($this->parent_id === 0) {
            $outputDir .= '/'.$this->uuid;
        } else {
            $outputDir .= '/'.$this->parentTask()->first()->uuid;
        }
        return $outputDir;
    }

    public function startFrames() {
        return DkvideoHelper::computeStartFrames($this->payload['start_frame'], $this->payload['time_alignment']);
    }

    public function configDir() {
        $snDir = DkvideoHelper::evalSerialNumberDir($this->payload['video_dir']);
        $base = config('task.config_dir');
        if(substr($base, strlen($base) - 1) !== '/') {
            $base .= '/';
        }
        return $base.$snDir;
    }
}
