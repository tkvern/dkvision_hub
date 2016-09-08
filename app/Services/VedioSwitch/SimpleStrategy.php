<?php
/**
 * Created by PhpStorm.
 * User: liujun
 * Date: 16/9/7
 * Time: 下午10:58
 */
namespace App\Services\VideoSwitch;

use App\Contacts\VideoSwitch\Strategy;
use App\Jobs\VideoSwitch;

class SimpleStrategy implements  Strategy {
    public function handle($task)
    {
        dispatch((new VideoSwitch($task))->onQueue('videos'));
    }
}

