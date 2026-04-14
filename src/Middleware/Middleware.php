<?php 

namespace App\Middleware;

use App\Http\Request; 

interface Middleware {


void  handle(Request $request, \Closure $next);

}
