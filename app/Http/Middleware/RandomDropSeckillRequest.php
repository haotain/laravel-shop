<?php

namespace App\Http\Middleware;

use App\Exceptions\InvalidRequestException;
use Closure;
use Illuminate\Http\Request;

class RandomDropSeckillRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @percent 参数是在路由添加中间件时传入
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $percent)
    {
        if (random_int(0, 100) < (int)$percent) {
            throw new InvalidRequestException('参与的用户过多，请稍后再试', 403);
        }
        return $next($request);
    }
}
