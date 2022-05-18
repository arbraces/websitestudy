<?php

namespace App\Http\Controllers;

class ServerController extends Controller
{
    public function getServerInfo(){
        $info = [
            '操作系统'=>php_uname('s'),
            'LARAVEL版本'=>app()::VERSION,
            'PHP版本'=>phpversion(),
            '时区'=>date_default_timezone_get(),
        ];
        return $this->returnSuccess($info);
    }
}
