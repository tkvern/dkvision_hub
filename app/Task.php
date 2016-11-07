<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Traits\TaskCommand;

class Task extends Model
{
    use TaskCommand;

    const WAITING = 1;
    const RUNNING = 2;
    const FINISH = 3;
    const ERROR = 4;
    const UNKNOWN = 5;

    public static $RENDER_TYPE = [
        'VISIONDK_3D' => '3D_FAST', 
        'VISIONDK_2D' => '2D_FAST', 
        'FACEBOOK_3D' => '3D_BETTER', 
        'FACEBOOK_2D' => '2D_BETTER', 
        'PREVIEW' => 'PREVIEW',
        'TOP_BOTTOM' => 'TOP_BOTTOM'
    ];

    public static $ALL_STATUS = [
        Task::WAITING => "等待中",
        Task::RUNNING => "运行中",
        Task::FINISH => "完成",
        Task::ERROR => "失败",
        Task::UNKNOWN => "未知"
    ];

    public static $STATUS_CLASS = [
        Task::WAITING => "primary",
        Task::RUNNING => "info",
        Task::FINISH => "success",
        Task::ERROR => "danger",
        Task::UNKNOWN => "warning"
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'processed_at'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    private $_process;

    public function creator() {
        return $this->belongsTo('App\User', 'creator_id');
    }

    public function subTasks() {
        return $this->hasMany('App\Task', 'parent_id');
    }

    public function parentTask() {
        return $this->belongsTo('App\Task', 'parent_id');
    }

    public function attachedTasks() {
        return $this->hasMany('App\Task', 'attach_id');
    }

    public function dependTask() {
        return $this->belongsTo('App\Task', 'attach_id');
    }

    public function human_status() {
        return array_get(self::$ALL_STATUS, $this->status, "未知");
    }

    public function status_class() {
        return array_get(self::$STATUS_CLASS, $this->status, "warning");
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
            return $this->_process = $this->processed;
        }
        if($this->status === self::ERROR && ! is_null($this->processed_at)) {
            return $this->_process = $this->processed;
        }
        if( !is_null($this->processed_at) && 
                Carbon::now()->diffInSeconds($this->processed_at) < 60) {
            return $this->_process = $this->processed;
        }
        $this->processed = $this->calcProcessing();
        $this->processed_at = Carbon::now();
        $this->save();
        return $this->_process = $this->processed;
    }

    public function calcProcessing() {
        $totalFrames = $this->payload['end_frame'] - $this->payload['start_frame'] + 1;
        $targetDir = $this->targetDir();
        $startFrame = $this->findStartFrame();
        $finishFrames = 0;
        if ($startFrame !== intval($this->payload['start_frame'])) {
            $finishFrames = $startFrame - intval($this->payload['start_frame']) + 2;
        } 
        info("==$targetDir== state: [$finishFrames/$totalFrames]");
        return floor($finishFrames*100/$totalFrames);
    }

    public function canDelete() {
        return ($this->status === self::WAITING ||
                $this->status === self::ERROR ||
                $this->status === self::FINISH);
    }

    public function canRetry() {
        return ($this->status !== self::WAITING);
    }

    public function canTerminate() {
        return $this->status === self::RUNNING;
    }

    public function canCreateTopAndBottom() {
        return ( $this->status === self::FINISH && 
                 !in_array($this->payload['task_type'], ['TOP_BOTTOM', 'PREVIEW']));
    }

    public function isEmergency() {
        return $this->priority > 100;
    }

}
