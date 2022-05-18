<?php


namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Sort;
use App\Models\Video;
use Facade\Ignition\Solutions\SolutionTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\FormRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\Finder\Iterator\SortableIterator;

class VideoController extends Controller
{
    //--------------初始化数据start--------------
    public $videoFolder;        //视频文件夹目录
    public $publicVideoFolder;  //公共视频路径

    //构造函数初始化数据
    public function __construct()
    {
        $this->videoFolder = public_path('assets\video');
        $this->publicVideoFolder = "http://"."192.168.1.50".'/websitestudyServe/public/assets/video';
    }
    //--------------初始化数据end--------------

    //添加视频(需要提交分类和视频地址)
    public function addVideo(Request $request){
        //是否提交相应的请求
        if (!($request->has(['fileUrl','sort','token']))){
            return $this->returnError('没有提交相应的请求参数');
        }
        $fileUrl = $request->input('fileUrl');
        $sort = $request->input('sort');
        $listName = $this->getFileName($fileUrl);
        $collection_id = uniqid();
        $cover = $fileUrl."/".'Cover.jpg';


        //判断提交的视频链接是否正确
        if (!(file_exists($fileUrl))){
            return $this->returnError('文件地址不存在');
        }

        //复制文件
        if(!($this->copyFile($fileUrl,public_path('assets\video')))){
            return $this->returnError('文件操作失败');
        }

        //把文件插入到数据库
        $disk = Storage::disk('public');
        $data = [];
        $fileList = $disk->files('video/'.$listName);
        //插入分类数据
        DB::table('sort')->insert([
           'id'     =>  null,
           'sort'   =>  $sort,
           'cover'  =>  file_exists($cover)?$this->publicVideoFolder."/".$listName."/".'Cover.jpg':$this->publicVideoFolder."/".$listName."/".'Cover.png',
           'name'   =>  $listName,
           'ctetime' => date("Y-m-d"),
           'collection_id' => $collection_id,
        ]);
        //对数组进行排序
        array_multisort($fileList,SORT_ASC, SORT_NATURAL);
        foreach ($fileList as $file){
            if ($this->getFileName($file) !== "Cover.jpg" && $this->getFileName($file) !== "Cover.png"){
                array_push($data,
                    ['id'    => null,
                    'title' => $this->getFileName($file),
                    'url'   => $this->publicVideoFolder."/".$listName."/".$this->getFileName($file),
                    'collection_id' => $collection_id,
                    'is_finish' => '[]'
                    ]);
            }
        }
        Video::insert($data);
        return $this->returnSuccess();
    }

    //删除视频(需要提交视频id)
    public function deleteVideo(Request $request){
        $collection_id = $request->input('collection_id');
        if (!$collection_id){
            return $this->returnError('没有提交视频id');
        }

        $data = Sort::where('collection_id',$collection_id);
        if (!$data->exists()){
            return $this->returnError('找不到该视频');
        }

        //删除数据库
        $pathName = $data->first('name')->name;
        $data->delete();
        Video::where('collection_id',$collection_id)->delete();

        //删除文件
        $disk = Storage::disk('public');
        foreach ($disk->allFiles('video'.'/'.$pathName) as $allFile) {
            $disk->delete($allFile);
        }
        $disk->deleteDirectory('video'.'/'.$pathName);

        return $this->returnSuccess();

    }
    //获取视频信息
    public function indexVideo(Request $request){
        return $this->returnSuccess(DB::table('sort')->select(
            'id',
            'sort',
            'cover',
            'name',
            'collection_id',
            'ctetime'
        )->get()->groupBy('sort'));
    }

    //获取视频详细信息 -- 需要提交视频id
    public function getVideo(Request $request,$id){
        if (!($id)){
            return $this->returnError('没有提交相应的请求');
        }
        $sql = Video::where('collection_id',$id);
        if (!($sql->exists())){
            return $this->returnError('找不到此视频');
        }
        $userId = $this->resTokenId($request->input('token'));

        $collection = $sql->get();
        $collection->transform(function ($item, $key) use ($userId) {
            if (in_array($userId, json_decode($item->is_finish))){
                $item->is_finish = true;
                return $item;
            }else{
                $item->is_finish = false;
                return $item;
            };
        });
        return $this->returnSuccess($collection);

    }

    //视频学习完成 -- 需要提交视频id
    public function finishVideo(Request $request){
        //判断提交的数据是否存在
        if (!($request->has(['videoId','token']))){
            return $this->returnError('没有提交视频id');
        }
        //根据token返回用户id
        $userId = $this->resTokenId($request->input('token'));
        $videoId = $request->input('videoId');

        //判断数据库是否插入成功
        if(!($this->sqlFinishVideo($userId, $videoId))){
            return $this->returnError('数据库插入失败');
        }

        return $this->returnSuccess();
    }

    //获取视频列表
    public function getVideoList(){
        return $this->returnSuccess(Sort::all());
    }
}
