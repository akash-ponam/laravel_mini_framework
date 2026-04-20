<?php 

namespace App\Middleware;

use App\Http\Request;

use App\Traits\ApiResponse;

use Closure;

class AuthMiddleware implements Middleware {


use ApiResponse;

public function handle(Request $request, Closure $next)
{

            if(session_status() === PHP_SESSION_NONE){
                
                session_start();
    
            }                

                            /* $_SESSION['user_id']  =  $user ['id']; */
                            /**/
                            /* $_SESSION['is_loggedin']  =  $user ['email'];       */
        
    if(!isset($_SESSION['user_id'])  || !isset($_SESSION['is_loggedin']) ) {


          $this->error_response('unatuhorized acces',401);
            exit;   

        }

 }


public function check_session() {



            if(session_status() === PHP_SESSION_NONE){
                
                session_start();
    
            }                

                         
        
    if(!isset($_SESSION['user_id'])  || !isset($_SESSION['is_loggedin']) ) {


          $this->error_response('unatuhorized acces',401);
            exit;   

        }

        
        $this->return_json([],201,'acess allowed babua');


}


}


