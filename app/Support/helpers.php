<?php

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;

use App\Task;

if(! function_exists('uuid1')) {
    function uuid1() {
        $func_args = func_get_args();
        return Uuid::uuid1(...$func_args)->toString();
    }
}

if(! function_exists('uuid3')) {
    function uuid3() {
        $func_args = func_get_args();
        return Uuid::uuid3(...$func_args)->toString();
    }
}

if(! function_exists('uuid4')) {
    function uuid4() {
        $func_args = func_get_args();
        return Uuid::uuid4(...$func_args)->toString();
    }
}

if(! function_exists('uuid5')) {
    function uuid5() {
        $func_args = func_get_args();
        return Uuid::uuid5(...$func_args)->toString();
    }
}

if(! function_exists('directory_file_count')) {
    /**
     * 计算目录中常规文件的数量
     *
     * @param string $dir
     * @return int
     */
    function directory_file_count($dir) {
        if(!is_dir($dir)) {
            return 0;
        }
        $dirIterator = new DirectoryIterator($dir);
        $count = 0;
        foreach($dirIterator as $fileInfo) {
            if(! $fileInfo->isDot() && ! $fileInfo->isDir()) {
                $count++;
            }
        }
        return $count;
    }
}

if(! function_exists('transaction_save_many')) {
    /**
     * 在事务中保存多个Eloguent ORM model
     * @param array Illuminate\Database\Eloquent\Model $models
     */
    function transaction_save_many($models)
    {
        DB::transaction(function () use ($models) {
            array_map(function ($model) {
                $model->save();
            }, $models);
        });
    }
}

if(!function_exists('array_copy')) {
    /**
     * 数组浅拷贝
     * @param array $arr
     * @return array
     */
    function array_copy(array $arr) {
        $copyArr = [];
        foreach ($arr as $key => $value) {
            $copyArr[$key] = $value;
        }
        return $copyArr;
    }
}

if(! function_exists('is_true')) {
    function is_true($val, $return_null=false){
        $boolval = ( is_string($val) ? filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : (bool) $val );
        return ( $boolval === null && !$return_null ? false : $boolval );
    }
}

if(! function_exists('active_or_not')) {
    function active_or_not($value) {
        return is_true($value) ? 'active' : '';
    }
}

if(!function_exists('option_selected')) {
    function option_seleted($selected) {
        return $selected ? 'selected' : '';
    }
}


if(!function_exists('box_checked')) {
    function box_checked($checked) {
        return $checked ? 'checked' : '';
    }
}

if (!function_exists('join_paths')) {
    function join_paths() {
        $paths = array();
        foreach (func_get_args() as $arg) {
            if ($arg !== '') { $paths[] = $arg; }
        }
        return preg_replace('#/+#','/',join('/', $paths));
    }
}

if (!function_exists('get_server_ips')) {
    function get_server_ips($localhost=false) {
        if (PHP_OS === 'Darwin') {
            exec('/sbin/ifconfig |grep \'inet \'| awk \'{ print $2}\'', $arr);
        } else {
            exec('/sbin/ifconfig |grep \'inet \'| awk \'{print $2}\'|awk -F: \'{print $2}\' ',$arr);
        }
        if (!$localhost) {
            $arr = array_values(array_filter($arr, function($ip) {
                return $ip != '127.0.0.1';
            }));
        }
        return $arr;
    }
}

function has_error_class($errors, $key)
{
    if ($errors->has($key)) {
        return 'has-error';
    } else {
        return '';
    }
}
function error_block($errors, $key)
{
    if ($errors->has($key)) {
        return "<span class='help-block'> {$errors->first($key)} </span>";
    } else {
        return '';
    }
}
