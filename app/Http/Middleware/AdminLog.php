<?php
/**
 * Created by PhpStorm.
 * User: yanglei
 * Date: 16/1/8
 * Time: 下午2:12
 */

namespace App\Http\Middleware;

use Closure;

class AdminLog
{

    /**
     * Create a new filter instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}