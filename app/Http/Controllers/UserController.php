<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

use App\Http\Requests;

class UserController extends Controller
{
    public function profile() {
        return view('user.profile');
    }

    public function updateProfile(Request $request) {
        $this->updateValidator($request)->validate();
        $admin = $request->user();
        $admin->name = $request->input('name');
        if (!empty($request->input('new_password'))) {
            $admin->password = bcrypt($request->input('new_password'));
        }
        $admin->save();
        Session::flash('flash_message_success', '信息更新成功');
        return redirect()->back();
    }

    private function updateValidator(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'old_password' => 'required',
        ]);
        $validator->sometimes('new_password', 'min:6|confirmed', function($input) {
           return !empty($input->new_password);
        });
        $validator->after(function($validator) use ($request) {
            if (!password_verify($request->input('old_password'), $request->user()->getAuthPassword())) {
                $validator->errors()->add('old_password', "原始密码不正确");
            }
        });
        $validator->setAttributeNames([
           'new_password' => '新密码'
        ]);
        return $validator;
    }
}
