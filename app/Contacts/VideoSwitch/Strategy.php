<?php
/**
 * Created by PhpStorm.
 * User: liujun
 * Date: 16/9/7
 * Time: 下午10:54
 */
namespace App\Contacts\VideoSwitch;

interface Strategy {
    /**
     * handle task
     *
     * @param App\Task $task
     * @return void
     */
    public function handle($task);
}