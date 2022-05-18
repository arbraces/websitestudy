<?php


namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //登录 -- 需要传入账号和密码
    public function login(Request $request){
        $rule = FormRule::loginRule($request);

        //进行表单验证
        if(!($rule['state'])){
            return  $this->returnFailRule($rule['message']);
        }


        $userData = \DB::table('user')->where(['username'=>$request->input('username'),
                                               'password'=>md5($request->input('password'))]);

        //判断账号密码是否正确
        if(!($userData->exists())){
            return $this->returnError('账号或密码错误');
        }

        //设置token并返回给客户端
        $userData->update(['token'=>md5($request->input('username'))]);
        return $this->returnSuccess($userData->select('token','is_admin')->first());
    }

    //注册 -- 需要传入账号密码，是否是管理员
    public function register(Request $request){
        $rule = FormRule::registerRule($request);

        //进行表单验证
        if(!($rule['state'])){
            return  $this->returnFailRule($rule['message']);
        }

        //插入数据库返回成功
        $user = new User;
        if ($user->where('username',$request->input('username'))->exists()){
            return $this->returnError('账号重复');
        }else{
            $user->username = $request->input('username');
            $user->password = md5($request->input('password'));
            $user->is_admin = $request->input('is_admin');
            $user->token = "";
            $user->save();
            return  $this->returnSuccess();
        }

    }

    //退出登录 -- 需要传入token
    public function logout(Request $request){
        $id = $this->resTokenId($request->input('token'));
        User::where('id',$id)
            ->update(['token'=>""]);

        return $this->returnSuccess();
    }

    //获取所有用户信息
    public function getUserInfoAll(){
        return $this->returnSuccess(User::get(['id','username','is_admin','updated_at', 'created_at']));
    }

    //删除用户 -- 需要提交删除用户的id
    public function deleteUser(Request $request){
        //判断是否提交指定字段
        if (!($request->has('id'))){
            return $this->returnError('没有提交用户id');
        }

        //判断账号是否存在
        $user = User::where('id',$request->input('id'));
        if (!($user->exists())){
            return $this->returnError('账号不存在');
        }

        //判断是否是删除自己
        if ($this->resTokenId($request->input('token')) == $request->input('id')){
            return $this->returnError('你不能删除自己');
        }

        //删除用户
        $user->delete();
        return $this->returnSuccess();
    }

    //修改密码
    public function putPassword(Request $request){
        if (!($request->has(['password', 'newpassword', 'compassword']))){
            return $this->returnError('没有提交指定的字段');
        }

        //进行表单验证
        $rule = FormRule::putPasswordRule($request);
        if(!($rule['state'])){
            return  $this->returnFailRule($rule['message']);
        }

        //判断旧密码是否正确
        $userDatabase = User::where('id', $this->resTokenId($request->input('token')));
        if ($userDatabase->first()->password !== md5($request->input('password'))){
            return $this->returnError('旧密码错误');
        }

        //判断两次密码是否一致
        if ($request->input('newpassword') !== $request->input('compassword')){
            return $this->returnError('两次密码不一致');
        }

        //插入新密码并删除token
        $userDatabase->update([
            'password'=>md5($request->input('newpassword'))
        ]);
        $userDatabase->update([
            'token'=>""
        ]);

        return $this->returnSuccess();
    }

}
