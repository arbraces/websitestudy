<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormRule extends Controller
{
    //登录规则验证
    public function loginRule($request){
        $validator = \Validator::make($request->all(), [
            'username' => 'required|min:2|max:5',
            'password' => 'required|min:6|max:18',
        ]);
        if ($validator->fails()) {
            return ['state' => false, 'message' => $validator->errors()];
        } else {
            return ['state' => true];
        }
    }

    //注册规则验证
    public function registerRule($request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|min:2|max:5',
            'password' => 'required|min:6|max:18',
            'is_admin' => 'required|numeric|between: 0,1',
        ]);
        if ($validator->fails()) {
            return ['state' => false, 'message' => $validator->errors()];
        } else {
            return ['state' => true];
        }
    }

    //修改密码规则验证
    public function putPasswordRule($request)
    {
        $validator = \Validator::make($request->all(), [
            'password' => 'required|min:6|max:18',
            'newpassword' => 'required|min:6|max:18'
        ]);
        if ($validator->fails()) {
            return ['state' => false, 'message' => $validator->errors()];
        } else {
            return ['state' => true];
        }
    }

}
