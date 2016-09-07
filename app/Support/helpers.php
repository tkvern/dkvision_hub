<?php
use Ramsey\Uuid\Uuid;

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
