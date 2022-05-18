<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $publicUrl;  //公共url地址

    //构造函数
    public function __construct()
    {
        $this->publicUrl = url('assets');
    }
    //-----------------用户类-----------------
    //判断token是否存在,如果有就返回token
    public function isToken(){
        if(isset($_GET['token'])){
            return $_GET['token'];
        }else{
            return false;
        }
    }

    //判断是否是管理员的token，需要传入管理员的token
    public function ifToken(string $token)
    {
        return \DB::table('user')->select('is_admin')->where('token', $token)->first()->is_admin;
    }

    //根据token返回用户的id，需要传入token
    public function resTokenId(string $token){
        $data =\DB::table('user')->select('id')->where('token',$token);
        if($data->exists()){
            return $data->first()->id;
        }else{
            return false;
        }
    }



    //-----------------返回接口-----------------
    //返回成功的信息
    public function returnSuccess($data=false)
    {
        if ($data){
            return response(json_encode(['msg' => '成功', 'data' => $data, 'state'=>1]));
        }else{
            return response(json_encode(['msg' => '成功', 'state'=>1]));
        }
    }

    //返回错误信息
    public function returnError(string $str)
    {
        return response(json_encode(['msg' => $str, 'state'=>2]));
    }

    //返回需要登录的信息
    public function returnLogin(){
        return response(json_encode(['msg' => '你还没有登录', 'state'=>3]));
    }

    //返回用户权限不够的信息
    public function returnNotRoot(){
        return response(json_encode(['msg' => '你不是管理员用户', 'state'=>4]));
    }

    //返回表单验证未通过信息
    public function returnFailRule($str){
        return response(json_encode(['msg' => $str, 'state'=>5]));
    }

    //获取文件名
    public function getFileName($url)
    {
        return preg_replace('/^.+[\\\\\\/]/', '', $url);
    }

    //对多维数组进行排序，需要传入有数组，还有用于排序的key，还有排序方式
    public function arraySort($array, $keys, $sort = SORT_ASC) {
        $keysValue = array();
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        foreach ($array as $key=>$value){
            unset($array[$key]["key"]);
        }
        return $array;
    }

    //获取一个唯一值
    public function randomRtnstr(){
        return md5(time().uniqid(). mt_rand(1,1000000));
    }

    //复制文件，需要传入文件地址，还有目标地址
    public function copyFile($path, $targetPath)
    {
        //文件操作句柄
        $targetDisk = Storage::build([
            'driver'  => 'local',
            'root'  => $targetPath
        ]);
        $disk = Storage::build([
            'driver'  => 'local',
            'root'  => $path
        ]);

        //在目标文件夹新建该目录
        $list = basename($path);
        $targetDisk->makeDirectory($list);

        //开始复制文件
        foreach ($disk->allFiles() as $item){
            if(!(copy($path."/".$item, $targetPath."/".$list."/".$item))){
                return false;
            }
        }
        return true;
    }



    //数据库操作类
    //学习完成视频，需要传入用户id，还有视频id
    public function sqlFinishVideo($userId, $videoId){
        $sql = \DB::table('video')->where('id',$videoId);
        //判断视频是否存在
        if (!($sql->exists())){
            return false;
        }

        $finishData = json_decode($sql->first()->is_finish);
        if (in_array($userId, $finishData)){
            return true;
        }else{
            array_push($finishData, $userId);
            $sql->update(["is_finish"=>json_encode($finishData)]);
        }
        return true;
    }

}
