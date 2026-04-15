<?php 

namespace App\Middleware;

use App\Http\Request;

use Closure;

class LogMiddleware implements Middleware {


    public function handle(Request $request,Closure $next){

        echo "dekh lo log is incomin lmfao ";
        return $next($request);
                  
    } 


}
