<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*            状态码说明

            1       正常
            2       逻辑错误(会有返回信息)
            3       认证错误，需要登录
            4       权限不够
            5       表单验证未通过

*/


//**********************用户**********************
//登录接口
Route::post('/login','GeneralController@login');
//注册
Route::post('/register', 'GeneralController@register');
//退出登录
Route::post('/logout','GeneralController@logout')->middleware('isToken');
//获取用户信息(管理员)
Route::get('/user', 'GeneralController@getUserInfoAll')->middleware('isAdminToken');
//删除用户(管理员)
Route::delete('/user', 'GeneralController@deleteUser')->middleware('isAdminToken');
//修改密码
Route::put('/user', 'GeneralController@putPassword')->middleware('isToken');


//**********************视频**********************
//添加视频(管理员)
Route::post('/video','VideoController@addVideo')->middleware('isAdminToken');
//获取视频信息
Route::get('/video','VideoController@indexVideo')->middleware('isToken');
//删除视频(管理员)
Route::delete('/video','VideoController@deleteVideo')->middleware('isAdminToken');
//获取视频详细信息
Route::get('/video/{id}', 'VideoController@getVideo')->middleware('isToken');
//视频学习完成
Route::post('/video/finish', 'VideoController@finishVideo')->middleware('isToken');
//获取视频列表
Route::get('/videolist', 'VideoController@getVideoList')->middleware('isToken');


//**********************项目资源**********************
//上传资源
Route::post('/project', 'ProjectController@addProject')->middleware('isAdminToken');
//获取资源
Route::get('/project', 'ProjectController@getProject')->middleware('isToken');
//删除资源
Route::delete('/project', 'ProjectController@deleteProject')->middleware('isAdminToken');
//下载资源
Route::get('/projectfile', 'ProjectController@getProjectFile')->middleware('isToken');



//**********************作业(学生上传的项目)**********************
//上传作业
Route::post('/take', 'TakeController@addTake')->middleware('isToken');


//**********************服务器**********************
//获取服务器信息(管理员)
Route::get('/server', 'ServerController@getServerInfo')->middleware('isAdminToken');
