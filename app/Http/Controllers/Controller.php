<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function successJsonResponse(array $extra = null) {
        $ret = ['err_code' => '0', 'err_msg' => 'SUCCESS'];
        if (!empty($extra)) {
            array_merge($ret, $extra);
        }
        return response()->json($ret);
    }

    protected function errorJsonResponse($code, $message) {
        return response()->json(['err_code' => $code, 'err_msg' => $message]);
    }
}
