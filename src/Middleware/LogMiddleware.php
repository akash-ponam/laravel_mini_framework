<?php 

namespace App\Middleware;

use App\Http\Request;


class LogMiddleware implements Middleware {


    public function handle(Request $request,\Closure $next){

          error_log("REQUEST RECIEVED INSIDE LOG ".$request->url());

        return $next($request);
                  
    } 


}
