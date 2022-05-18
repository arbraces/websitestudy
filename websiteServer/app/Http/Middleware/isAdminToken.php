<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class isAdminToken extends Controller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //判断token是否存在并且正确
        if ($request->has('token')){
            $isToken = User::where('token',$request->input('token'));
            if ($isToken->exists()){
                if($this->ifToken($request->input('token'))){
                    return $next($request);
                }else{
                    return $this->returnNotRoot();
                }
            }else{
                return $this->returnLogin();
            }
        }else{
            return $this->returnLogin();
        }
    }
}
