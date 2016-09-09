<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Utils\DkvideoHelper;

class Task extends Model
{
    const WAITING = 1;
    const RUNNING = 2;
    const FINISH = 3;
    const ERROR = 4;
    const UNKNOWN = 5;

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
        $totalFrames = $this->payload['start_frame'] - $this->payload['end_frame'] + 1;
        $outputDir = $this->outputDir();
        $finishFrames = directory_file_count($outputDir);
        return $this->_process = floor($finishFrames*100/$totalFrames);
    }

    public function outputDir() {
        $outputDir = DkvideoHelper::getOutputDir($this->payload['video_dir']);
        if($this->parent_id === 0) {
            $outputDir .= '/'.$this->uuid;
        } else {
            $outputDir .= '/'.$this->parentTask()->uuid;
        }
        return $outputDir;
    }

    public function startFrames() {
        return DkvideoHelper::computeStartFrames($this->payload['start_frame'], $this->payload['time_alignment']);
    }

    public function configDir() {
        $snDir = DkvideoHelper::evalSerialNumberDir($this->payload['video_dir']);
        return '/data/config/'.$snDir;
    }
}
