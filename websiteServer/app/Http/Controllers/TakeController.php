<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class TakeController extends Controller
{
    //上传作业 -- 需要提交项目id,文件
    public function addTake(Request $request){
        /*
         * 需要提交的数据库字段
         * id, project_id, task_name, user_id
         */
        //表单验证
        if(!($request->has(['project_id','file']))){    //判断是否提交指定的字段
            return $this->returnError('缺少相应字段');
        }
        $projectDataBase = Project::where('id',$request->input('project_id'));
        if (!($projectDataBase->exists())){    //判断项目id是否存在
            return $this->returnError('找不到该数据');
        }
        $userDataBase = User::where('id', $this->resTokenId($request->input('token')));
        if (!($userDataBase->exists())){
            return $this->returnError('找不到该数据');
        }
        $project_name = $projectDataBase->first()->project_name;
        $user_name = $userDataBase->first()->username;
        $project_id = $projectDataBase->first()->id;
        $user_id = $userDataBase->first()->id;

        //开始处理文件
        $file = $request->file('file'); //用户上传的文件
        $ext = $file->extension(); //上传文件的扩展名
        if (!($ext == "zip")){  //判断文件扩展名是否正确
            return $this->returnError('请上传正确的文件!');
        }

        if ($file->isValid()){  //判断文件是否上传成功
            //将用户的文件保存到本地
            $disk = Storage::disk('public');
            $filename = $project_name."-".$user_name.".".$ext;
            $disk->putFileAs('take',$file,$filename);  //把用户上传的文件保存到本地

            //插入数据库
            //判断是否重复提交
            $ifTask =Task::where([['user_id',"=" ,$user_id], ['project_id',"=",$project_id]]);
            if (!($ifTask->exists())){
                $taskDataBase = new Task;
                $taskDataBase->id = null;
                $taskDataBase->project_id = $request->input('project_id');
                $taskDataBase->task_name = $filename;
                $taskDataBase->task_file = $this->publicUrl."/"."take"."/".$filename;
                $taskDataBase->user_id = $user_id;
                $taskDataBase->save();
            }
            return $this->returnSuccess();
        }else{
            return $this->returnError('文件上传失败');
        }

    }
}
