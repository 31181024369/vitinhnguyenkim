<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\StatisticUserOnline;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class MemberMiddleWare
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
        //return 11;
        //var_dump($this->getIp());exit;
        // if(!Auth::guard('member')->check()){
            
        //     return $next($request);
        // }else {
        //     return 112;
        //     $url=URL::current();
        //     $now = date('Y-m-d H:i:s');
        //     $date = Carbon::now('Asia/Ho_Chi_Minh');
        //     $timestamp = strtotime($date);
        //     $statisticsuseronline=StatisticUserOnline::create([
        //         'ip'=> $request->ip(),
        //         'referred' => ltrim($request->path(),"api/"),
        //         'timestamp' => $timestamp,
        //         'date'=> $now,
        //         'agent'=>'Mozilla'
        //     ]);
        //     return $next($request);
        // }
      
    }

   
}
