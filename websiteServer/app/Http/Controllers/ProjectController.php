<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    //添加资源
    public function addProject(Request $request){
        if(!($request->has(['project_name','project_file','project_needstudy']))){
            return $this->returnError('缺少相应字段');
        }
        $project_name = $request->input('project_name');
        $project_needstudy = $request->input('project_needstudy');
        $ctetime = date('Y-m-d ');

        //开始处理文件
        $file = $request->file('project_file');   //用户提交的文件
        $ext = $file->extension(); //上传文件的扩展名
        $randomFileName = $this->randomRtnstr();
        //判断文件扩展名是否正确
        if (!($ext == "zip")){
            return $this->returnError('请上传正确的文件!');
        }

        //判断文件是否上传成功
        if($file->isValid()){
            //将用户的文件保存到本地
            $disk = Storage::disk('public');
            $disk->putFileAs('project',$file,$randomFileName.".".$ext);  //把用户上传的文件保存到本地

            //将数据插入数据库
            $project_file = $this->publicUrl."/"."project"."/".$randomFileName.".".$ext;
            $projectDatabase = new Project;
            $projectDatabase->id = null;
            $projectDatabase->project_name = $project_name;
            $projectDatabase->project_file = $project_file;
            $projectDatabase->project_needstudy = empty($project_needstudy)?"":$project_needstudy;
            $projectDatabase->ctetime = $ctetime;
            $projectDatabase->save();

            //返回数据给前端
            return $this->returnSuccess();
        }else{
            return $this->returnError('文件上传失败');
        }

    }

    //获取资源列表
    public function getProject(Request $request){
        return $this->returnSuccess(Project::all());
    }

    //删除资源
    public function deleteProject(Request $request){
        if (!($request->has('id'))){
            return $this->returnError('没有提交相应的字段');
        }
        $dataBase = Project::where('id',$request->input('id'));
        if (!($dataBase->exists())){
            $this->returnError('找不到该数据');
        }
        //删除文件
        $fileName = $this->getFileName($dataBase->get('project_file')[0]->project_file);
        $disk = Storage::disk('public');
        $disk->delete('project/'.$fileName);

        //删除数据库
        $dataBase->delete();
        return $this->returnSuccess();
    }

    //下载资源
    public function getProjectFile(Request $request){
        //判断是否提交指定字段
        if(!($request->has('id'))){
            return $this->returnError('没有提交相应的字段');
        }

        $dataBase = Project::where('id',$request->input('id'));
        //判断资源是否存在
        if (!($dataBase->exists())){
            return $this->returnError('找不到该数据');
        }


        $rtnName = $dataBase->get('project_name')[0]->project_name.".zip";
        $fileName = $this->getFileName($dataBase->get('project_file')[0]->project_file);
        $disk = Storage::disk('public');
        return $disk->download('project/'.$fileName, $rtnName,
        [
            "Access-Control-Expose-Headers" => "Content-Disposition",
            "Content-Type" => 'application/json; application/octet-stream',
            "Content-Disposition" => rawurlencode($rtnName),
            "Content-Transfer-Encoding" => "binary"
        ]);
    }
}
